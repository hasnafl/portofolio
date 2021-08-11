from paramiko import SSHClient
from scp import SCPClient
ssh = SSHClient()
ssh.load_system_host_keys()
ssh.connect('root@149.129.216.16:/home/py')
with SCPClient(ssh.get_transport()) as scp:
    scp.put('modelhscode_exmp.pkl', 'modelhscode_exmp.pkl') # Copy my_file.txt to the server