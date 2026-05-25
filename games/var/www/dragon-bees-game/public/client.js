// ----------------------
// Setup & constants
// ----------------------
const canvas = document.getElementById("game");
const ctx = canvas.getContext("2d");
const socket = io();

const hpInnerEl = document.getElementById("hpInner");
const hpTextEl  = document.getElementById("hpText");
const scoreTextEl = document.getElementById("scoreText");
const teamTextEl  = document.getElementById("teamText");
const eggInnerEl  = document.getElementById("eggInner");
const eggTextEl   = document.getElementById("eggText");

const SPEED = 3;
const DRAGON_RADIUS = 18;
const BEE_RADIUS = 3;
const BEE_LIFE = 60;
const BEE_SPEED = 5;
const BASE_DAMAGE = 8;
const POISON_TICKS = 60;
const POISON_DPS = 0.5;

const RUST_SLOW_TICKS = 90;
const MOULD_POISON_MULT = 2.0;
const MOTHS_DEBUFF_TICKS = 120;
const DAMAGE_DEBUFF_MULT = 1.5;

const EGG_RAISE_TIME_SECONDS = 30;
const FPS = 60;
const EGG_PROGRESS_PER_FRAME = 1 / (EGG_RAISE_TIME_SECONDS * FPS);

// Teams
const TEAMS = [
  {
    id: 0,
    name: "Rustclaw",              // Red & White, bees
    colors: ["#ff4444", "#ffffff"],
    breath: "bees",
  },
  {
    id: 1,
    name: "Ironbane",              // Blue & Red, rust dust
    colors: ["#4477ff", "#ff4444"],
    breath: "rust_dust",
  },
  {
    id: 2,
    name: "Blightwing",            // Black & White, mould
    colors: ["#000000", "#ffffff"],
    breath: "mould",
  },
  {
    id: 3,
    name: "Threadcutter",          // Green & Red, moths
    colors: ["#33aa33", "#ff4444"],
    breath: "moths",
  },
];

function getTeam(teamId) {
  return TEAMS.find(t => t.id === teamId) || TEAMS[0];
}

// input & state
let myId = null;
const keys = {};
let mouseX = canvas.width / 2;
let mouseY = canvas.height / 2;

const players = {}; // id -> { x,y,angle,bees,hp,score,team,hasEgg,eggProgress,poison,slowTicks,defenseDebuffTicks }
let eggs = [];      // { id, x, y, team }

// ----------------------
// Input listeners
// ----------------------
window.addEventListener("keydown", (e) => {
  keys[e.key.toLowerCase()] = true;
});
window.addEventListener("keyup", (e) => {
  keys[e.key.toLowerCase()] = false;
});
canvas.addEventListener("mousemove", (e) => {
  const rect = canvas.getBoundingClientRect();
  mouseX = e.clientX - rect.left;
  mouseY = e.clientY - rect.top;
});
canvas.addEventListener("mousedown", () => {
  if (!myId || !players[myId]) return;
  shootBreath();
});

// ----------------------
// Socket events
// ----------------------
socket.on("connect", () => {
  myId = socket.id;
});

socket.on("currentState", (state) => {
  // Initialize players from server
  for (const id in state) {
    const data = state[id];
    players[id] = {
      ...data,
      poison: 0,
      slowTicks: 0,
      defenseDebuffTicks: 0,
      bees: (data.bees || []).map(b => ({ ...b })),
    };
  }
  updateMyTeamHud();
});

socket.on("eggsState", (serverEggs) => {
  eggs = serverEggs || [];
});

socket.on("newPlayer", ({ id, data }) => {
  players[id] = {
    ...data,
    poison: 0,
    slowTicks: 0,
    defenseDebuffTicks: 0,
    bees: [],
  };
});

socket.on("stateUpdate", ({ id, data }) => {
  const prev = players[id] || {};
  players[id] = {
    ...prev,
    ...data,
    poison: prev.poison || 0,
    slowTicks: prev.slowTicks || 0,
    defenseDebuffTicks: prev.defenseDebuffTicks || 0,
    bees: prev.bees || [],
  };
  if (id === myId) updateMyTeamHud();
});

socket.on("beesUpdate", ({ id, bees }) => {
  if (!players[id]) return;
  players[id].bees = (bees || []).map(b => ({
    ...b,
    hitMe: false,
  }));
});

socket.on("removePlayer", (id) => {
  delete players[id];
});

// ----------------------
// Helpers
// ----------------------
function updateMyTeamHud() {
  const me = players[myId];
  if (!me) return;
  const team = getTeam(me.team);
  teamTextEl.textContent = `Team: ${team.name}`;
}

function shootBreath() {
  const me = players[myId];
  if (!me) return;
  const team = getTeam(me.team);
  const projectiles = [];

  const swarmSize = 18;
  for (let i = 0; i < swarmSize; i++) {
    const spread = (Math.random() - 0.5) * 0.7;
    projectiles.push({
      x: me.x,
      y: me.y,
      vx: Math.cos(me.angle + spread) * BEE_SPEED,
      vy: Math.sin(me.angle + spread) * BEE_SPEED,
      life: BEE_LIFE,
      hitMe: false,
    });
  }

  me.bees = projectiles;
  socket.emit("shootBees", projectiles);
}

function randomSpawn() {
  return {
    x: 50 + Math.random() * (canvas.width - 100),
    y: 50 + Math.random() * (canvas.height - 100),
  };
}

// ----------------------
// Game update loop
// ----------------------
function update() {
  const me = players[myId];

  if (me) {
    const effectiveSpeed = me.slowTicks > 0 ? SPEED * 0.6 : SPEED;

    if (keys["w"]) me.y -= effectiveSpeed;
    if (keys["s"]) me.y += effectiveSpeed;
    if (keys["a"]) me.x -= effectiveSpeed;
    if (keys["d"]) me.x += effectiveSpeed;

    me.x = Math.max(DRAGON_RADIUS, Math.min(canvas.width - DRAGON_RADIUS, me.x));
    me.y = Math.max(DRAGON_RADIUS, Math.min(canvas.height - DRAGON_RADIUS, me.y));

    me.angle = Math.atan2(mouseY - me.y, mouseX - me.x);

    // status effects decay
    if (me.poison > 0) {
      me.hp -= POISON_DPS;
      me.poison--;
    }
    if (me.slowTicks > 0) me.slowTicks--;
    if (me.defenseDebuffTicks > 0) me.defenseDebuffTicks--;

    if (me.hp > 100) me.hp = 100;

    // death / respawn
    if (me.hp <= 0) {
      me.hp = 0;
      me.hasEgg = false;
      me.eggProgress = 0.0;
      const spawn = randomSpawn();
      me.x = spawn.x;
      me.y = spawn.y;
      me.hp = 100;
      me.poison = 0;
      me.slowTicks = 0;
      me.defenseDebuffTicks = 0;
      socket.emit("respawn", { x: me.x, y: me.y, hp: me.hp });
    }

    // projectiles from me
    if (me.bees) {
      me.bees.forEach((b) => {
        b.x += b.vx;
        b.y += b.vy;
        b.life--;
      });
      me.bees = me.bees.filter(b => b.life > 0);
    }

    // collisions: other players' projectiles hitting me
    for (const id in players) {
      if (id === myId) continue;
      const p = players[id];
      if (!p.bees) continue;
      const attackerTeam = getTeam(p.team);

      p.bees.forEach((proj) => {
        if (proj.life <= 0 || proj.hitMe) return;
        const dx = proj.x - me.x;
        const dy = proj.y - me.y;
        const distSq = dx*dx + dy*dy;
        const hitRadius = DRAGON_RADIUS + BEE_RADIUS;
        if (distSq <= hitRadius * hitRadius) {
          proj.hitMe = true;

          let dmg = BASE_DAMAGE;
          // apply status based on attack type
          switch (attackerTeam.breath) {
            case "rust_dust":
              dmg *= 0.6;
              me.slowTicks = RUST_SLOW_TICKS;
              break;
            case "mould":
              dmg *= 0.4;
              me.poison = POISON_TICKS * MOULD_POISON_MULT;
              break;
            case "moths":
              dmg *= 0.5;
              me.defenseDebuffTicks = MOTHS_DEBUFF_TICKS;
              break;
            case "bees":
            default:
              me.poison = POISON_TICKS;
              break;
          }

          if (me.defenseDebuffTicks > 0) {
            dmg *= DAMAGE_DEBUFF_MULT;
          }

          me.hp -= dmg;
          if (me.hp < 0) me.hp = 0;

          p.score = (p.score || 0) + 1;
        }
      });
    }

    // egg pickup if not carrying
    if (!me.hasEgg) {
      eggs.forEach((egg) => {
        if (egg.team !== me.team) return;
        const dx = egg.x - me.x;
        const dy = egg.y - me.y;
        const distSq = dx*dx + dy*dy;
        const pickupRadius = 22;
        if (distSq <= pickupRadius * pickupRadius) {
          socket.emit("pickEgg", { eggId: egg.id });
        }
      });
    }

    // egg raising progress
    if (me.hasEgg) {
      me.eggProgress = (me.eggProgress || 0) + EGG_PROGRESS_PER_FRAME;
      if (me.eggProgress > 1) me.eggProgress = 1;
      socket.emit("updateEggProgress", { eggProgress: me.eggProgress });
    }

    // HUD updates
    hpTextEl.textContent = Math.max(0, Math.round(me.hp));
    const hpScale = Math.max(0, Math.min(1, me.hp / 100));
    hpInnerEl.style.transform = `scaleX(${hpScale})`;

    scoreTextEl.textContent = Math.round(me.score || 0);

    if (me.hasEgg) {
      const eScale = Math.max(0, Math.min(1, me.eggProgress || 0));
      eggInnerEl.style.transform = `scaleX(${eScale})`;
      eggTextEl.textContent = `${Math.round(eScale * 100)}%`;
    } else {
      eggInnerEl.style.transform = "scaleX(0)";
      eggTextEl.textContent = "None";
    }

    // send movement & core stats to server
    socket.emit("move", {
      x: me.x,
      y: me.y,
      angle: me.angle,
      hp: me.hp,
      score: me.score || 0,
    });
  }

  // move projectiles for other players
  for (const id in players) {
    if (id === myId) continue;
    const p = players[id];
    if (!p.bees) continue;
    p.bees.forEach((b) => {
      b.x += b.vx;
      b.y += b.vy;
      b.life--;
    });
    p.bees = p.bees.filter(b => b.life > 0);
  }
}

// ----------------------
// Drawing
// ----------------------
function drawEggs() {
  eggs.forEach((egg) => {
    const team = getTeam(egg.team);
    const [c1, c2] = team.colors;

    ctx.save();
    ctx.translate(egg.x, egg.y);

    ctx.fillStyle = c1;
    ctx.beginPath();
    ctx.ellipse(0, 0, 10, 14, 0, 0, Math.PI * 2);
    ctx.fill();

    ctx.strokeStyle = c2;
    ctx.lineWidth = 3;
    ctx.beginPath();
    ctx.ellipse(0, 0, 8, 10, 0.3, 0, Math.PI * 2);
    ctx.stroke();

    ctx.restore();
  });
}

function drawBreath(p) {
  if (!p.bees) return;
  const team = getTeam(p.team);

  p.bees.forEach((proj) => {
    switch (team.breath) {
      case "rust_dust":
        ctx.fillStyle = "#ff9900";
        ctx.beginPath();
        ctx.arc(proj.x, proj.y, 3, 0, Math.PI * 2);
        ctx.fill();
        ctx.fillStyle = "#ffeecc";
        ctx.beginPath();
        ctx.arc(proj.x + 1, proj.y - 1, 1.5, 0, Math.PI * 2);
        ctx.fill();
        break;

      case "mould":
        ctx.fillStyle = "#66cc66";
        ctx.beginPath();
        ctx.arc(proj.x, proj.y, 4, 0, Math.PI * 2);
        ctx.fill();
        ctx.strokeStyle = "rgba(0,0,0,0.4)";
        ctx.beginPath();
        ctx.arc(proj.x, proj.y, 4, 0, Math.PI * 2);
        ctx.stroke();
        break;

      case "moths":
        ctx.fillStyle = "#ddddaa";
        ctx.beginPath();
        ctx.ellipse(proj.x - 1, proj.y, 3, 2, 0.3, 0, Math.PI * 2);
        ctx.fill();
        ctx.beginPath();
        ctx.ellipse(proj.x + 1, proj.y, 3, 2, -0.3, 0, Math.PI * 2);
        ctx.fill();
        break;

      case "bees":
      default:
        ctx.fillStyle = "#ffdd33";
        ctx.beginPath();
        ctx.arc(proj.x, proj.y, 3, 0, Math.PI * 2);
        ctx.fill();
        ctx.fillStyle = "#333";
        ctx.beginPath();
        ctx.arc(proj.x + 2, proj.y, 1.2, 0, Math.PI * 2);
        ctx.fill();
        break;
    }
  });
}

function drawDragon(p, isMe) {
  const team = getTeam(p.team);
  const bodyColor = team.colors[0];
  const accentColor = team.colors[1];

  ctx.save();
  ctx.translate(p.x, p.y);
  ctx.rotate(p.angle);

  // body
  ctx.fillStyle = bodyColor;
  ctx.beginPath();
  ctx.ellipse(0, 0, 18, 12, 0, 0, Math.PI * 2);
  ctx.fill();

  // head
  ctx.beginPath();
  ctx.moveTo(18, 0);
  ctx.lineTo(28, -6);
  ctx.lineTo(28, 6);
  ctx.closePath();
  ctx.fill();

  // wings
  ctx.globalAlpha = 0.6;
  ctx.fillStyle = accentColor;
  ctx.beginPath();
  ctx.ellipse(-6, -10, 14, 6, -0.5, 0, Math.PI * 2);
  ctx.fill();
  ctx.beginPath();
  ctx.ellipse(-6, 10, 14, 6, 0.5, 0, Math.PI * 2);
  ctx.fill();
  ctx.globalAlpha = 1;

  // rider silhouette
  ctx.fillStyle = "#222222";
  ctx.beginPath();
  ctx.arc(-2, -5, 4, 0, Math.PI * 2); // head
  ctx.fill();
  ctx.fillRect(-4, -5, 8, 9);         // torso
  ctx.fillStyle = accentColor;
  ctx.fillRect(-1, 2, 2, 5);          // saddle detail

  ctx.restore();
}

function render() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // background grid
  ctx.strokeStyle = "rgba(255,255,255,0.04)";
  ctx.lineWidth = 1;
  for (let x = 0; x < canvas.width; x += 40) {
    ctx.beginPath();
    ctx.moveTo(x, 0);
    ctx.lineTo(x, canvas.height);
    ctx.stroke();
  }
  for (let y = 0; y < canvas.height; y += 40) {
    ctx.beginPath();
    ctx.moveTo(0, y);
    ctx.lineTo(canvas.width, y);
    ctx.stroke();
  }

  // eggs
  drawEggs();

  // breath/projectiles
  for (const id in players) {
    const p = players[id];
    if (!p) continue;
    drawBreath(p);
  }

  // dragons
  for (const id in players) {
    const p = players[id];
    if (!p) continue;
    drawDragon(p, id === myId);
  }
}

// ----------------------
// Main loop
// ----------------------
function gameLoop() {
  update();
  render();
  requestAnimationFrame(gameLoop);
}

gameLoop();

