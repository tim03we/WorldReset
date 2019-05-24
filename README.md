# WorldReset

With WorldReset you can reset certain worlds completely lying at your choice in a certain time interval.
# Setup

- Convert your worlds folder into a ZIP archive.
- Pack the ZIP archive into the "worldreset" folder. You will find this folder in the server directory where the "server.properties" are located.
- Make sure that the ZIP archive has the same name as the Worlds folder and the Worlds name.
- Now restart the server.

# Config
```
# Set after how many minutes the world should be reset.
time: 60

# Please list the worlds in a list. Example:
# worlds:
#   - "world1"
#   - "world2"
# or
# worlds: ["world1", "world2"]
# Important: Make sure you use upper and lower case.
worlds: []

# Decide if a message should be sent when resetting the worlds.
# Use "true" or "false"
reset-message: true

# Set the worlds reset message.
# The message is only sent if "reset-message" is set to "true".
message: "The worlds have been reset."
```

----------------

If problems arise, create an issue or write us on Discord.

| Discord |
| :---: |
[![Discord](https://img.shields.io/discord/427472879072968714.svg?style=flat-square&label=discord&colorB=7289da)](https://discord.gg/Ce2aY25) |
