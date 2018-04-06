# Library from https://github.com/dpallot/simple-websocket-server
from SimpleWebSocketServer import SimpleWebSocketServer, WebSocket

eventListeners = dict();
class KYCServer(WebSocket):
    def handleMessage(self):
	command = self.data.split(":")
	if command[0] == "listen":
		eventid = int(command[1])
		if eventid not in eventListeners.keys():
			eventListeners[eventid] = list()
		eventListeners[eventid].append(self)
		print("\t" + str(self.address[0]) + " registered for event " + str(eventid))
	if command[0] == "unlisten":
		eventid = int(command[1])
		eventListeners[eventid].remove(self)
		print("\t" + str(self.address[0]) + " unregistered for event " + str(eventid))
	if command[0] == "update":
		eventid = int(command[1])
		print("\tReceived update for event " + str(eventid));
		if eventid not in eventListeners.keys():
			return
		for s in eventListeners[eventid]:
			print("\t\tSending update to " + str(s.address[0]));
			s.sendMessage(self.data)
		self.sendMessage(unicode("done"));
		
    def handleConnected(self):
        print("Client " + self.address[0] +  ' connected')

    def handleClose(self):
        print("Client " + self.address[0] + " disconnected")

print("Launching KYCServer on port 8080")
server = SimpleWebSocketServer('', 8080, KYCServer)
server.serveforever()
