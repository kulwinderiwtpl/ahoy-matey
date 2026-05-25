
import os
import random
import socketio
import eventlet
from eventlet import wsgi

# -------------------
# Team definitions
# -------------------

TEAMS = [
    {
        "id": 0,
        "name": "BuzzeeBee",              # Red & White
        "colors": ["#ff4444", "#ffffff"],
        "breath": "bees",
    },
    {
        "id": 1,
        "name": "FerrousFeeder",              # Blue & Red
        "colors": ["#4477ff", "#ff4444"],
        "breath": "rust_dust",
    },
    {
        "id": 2,
        "name": "Blightwing",            # Black & White
        "colors": ["#000000", "#ffffff"],
        "breath": "mould",
    },
    {
        "id": 3,
        "name": "CottonCut",          # Green & Red
        "colors": ["#33aa33", "#ff4444"],
        "breath": "moths",
    },
]


def random_team_id():
    return random.choice(TEAMS)["id"]


# -------------------
# Game State
# -------------------

# Socket.IO server
sio = socketio.Server(cors_allowed_origins="*")
app = socketio.WSGIApp(sio)

# Players: sid -> dict
players = {}

# Eggs on the map
eggs = []  # list of { id, x, y, team }
_next_egg_id = 1


def spawn_egg(team_id=None):
    """Spawn an egg for given team (or random team) at random location."""
    global _next_egg_id
    if team_id is None:
        team_id = random_team_id()
    egg = {
        "id": _next_egg_id,
        "x": random.uniform(50, 750),
        "y": random.uniform(50, 550),
        "team": int(team_id),
    }
    _next_egg_id += 1
    eggs.append(egg)


# spawn one egg for each team at startup
for t in TEAMS:
    spawn_egg(t["id"])


def make_new_player():
    # team -1 means "has not selected a team yet"
    return {
        "x": random.random() * 800,
        "y": random.random() * 600,
        "angle": 0,
        "bees": [],
        "hp": 100,
        "score": 0,
        "team": -1,
        "hasEgg": False,
        "eggProgress": 0.0,
    }

# -------------------
# Socket.IO Events
# -------------------

@sio.event
def connect(sid, environ):
    print("Player connected:", sid)

    players[sid] = make_new_player()

    # Send players & eggs only to this new player
    sio.emit("currentState", players, room=sid)
    sio.emit("eggsState", eggs, room=sid)

    # Notify others about the new player
    sio.emit("newPlayer", {"id": sid, "data": players[sid]}, skip_sid=sid)


@sio.event
def move(sid, data):
    """
    data: { x, y, angle, hp, score }
    """
    if sid not in players:
        return

    p = players[sid]
    p["x"] = data.get("x", p["x"])
    p["y"] = data.get("y", p["y"])
    p["angle"] = data.get("angle", p["angle"])
    p["hp"] = data.get("hp", p["hp"])
    p["score"] = data.get("score", p["score"])

    sio.emit("stateUpdate", {"id": sid, "data": p})


@sio.event
def shootBees(sid, beeSwarm):
    """
    beeSwarm: list of projectile dicts sent by client
    Used generically for all breath types.
    """
    if sid not in players:
        return
    players[sid]["bees"] = beeSwarm
    sio.emit("beesUpdate", {"id": sid, "bees": beeSwarm})


@sio.event
def respawn(sid, data):
    """
    data: { x, y, hp }
    """
    if sid not in players:
        return
    p = players[sid]
    p["x"] = data.get("x", p["x"])
    p["y"] = data.get("y", p["y"])
    p["hp"] = data.get("hp", p["hp"])
    p["hasEgg"] = False
    p["eggProgress"] = 0.0
    sio.emit("stateUpdate", {"id": sid, "data": p})


@sio.event
def pickEgg(sid, data):
    """
    data: { eggId }
    Player tries to pick up an egg – server validates.
    """
    egg_id = data.get("eggId")
    if sid not in players:
        return

    player = players[sid]
    if player["hasEgg"]:
        return

    target = None
    for e in eggs:
        if e["id"] == egg_id:
            target = e
            break

    if not target:
        return

    if int(target["team"]) != int(player["team"]):
        return

    # Give egg to player
    player["hasEgg"] = True
    player["eggProgress"] = 0.0
    eggs.remove(target)

    sio.emit("stateUpdate", {"id": sid, "data": player})
    sio.emit("eggsState", eggs)


@sio.event
def updateEggProgress(sid, data):
    """
    data: { eggProgress }
    Client reports how far egg raising has progressed.
    """
    if sid not in players:
        return
    player = players[sid]
    if not player["hasEgg"]:
        return

    progress = float(data.get("eggProgress", player["eggProgress"]))
    if progress < 0.0:
        progress = 0.0
    if progress > 1.0:
        progress = 1.0

    player["eggProgress"] = progress

    if progress >= 1.0:
        # Egg fully raised
        player["hasEgg"] = False
        player["eggProgress"] = 0.0
        player["score"] += 5  # reward for raising an egg

        # Spawn a new egg for this team
        spawn_egg(player["team"])
        sio.emit("eggsState", eggs)

    sio.emit("stateUpdate", {"id": sid, "data": player})


@sio.event
def disconnect(sid):
    print("Player disconnected:", sid)
    if sid in players:
        del players[sid]
    sio.emit("removePlayer", sid)


# -------------------
# Server entrypoint
# -------------------

if __name__ == "__main__":
    port = int(os.environ.get("PORT", "5000"))
    print(f"Dragon Bee Swarm Python server listening on port {port}")
    wsgi.server(eventlet.listen(("", port)), app)

