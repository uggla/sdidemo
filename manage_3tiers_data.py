#!/usr/bin/python

'''
Update owncloud external storage

@author : rene.ribaud@hp.com
'''
import sys
import json
import pprint
import argparse
import os
import subprocess
import datetime


# Variable needed

confFile = '/home/sdi/ansible/datafile.json'
prog = sys.argv[0]

if __name__ == '__main__':

    def get_hostname(ip):
        cmd = "ssh -o StrictHostKeyChecking=no root@" + ip + " hostname"
	try:
            output = subprocess.check_output(cmd.split())
        except subprocess.CalledProcessError, e:
            return ""
        return output


    pp = pprint.PrettyPrinter(indent=4)

    parser = argparse.ArgumentParser(version = prog + ' version 1.0')
    
    parser.add_argument('action', action='store',
                        choices=['add','remove','get'],
                    help='Action to do either add,remove or get data')

    parser.add_argument('uuid', action='store',
                    help='3 tiers uuid')

    parser.add_argument('type', action='store',
                    help='tier type')

    parser.add_argument('ipaddress', action='store',
                    help='ipaddress of the tier')

    arguments = parser.parse_args()
    
    # Load json
    json_file = open(confFile,'r')
    datafile = json.loads(json_file.read())
    json_file.close()

    # Update variables according to args

    if (arguments.action == 'add'):
        if arguments.uuid not in datafile:
            datafile[arguments.uuid] = {arguments.type: arguments.ipaddress}
        else:
            datafile[arguments.uuid][arguments.type] = arguments.ipaddress
    datafile[arguments.uuid][arguments.type + "-hostname"] = get_hostname(arguments.ipaddress).strip()
    lastupdate = datetime.datetime.now()
    datafile[arguments.uuid]["lastupdate"] = lastupdate.strftime('%Y-%m-%d %H:%M:%S')
    

    if (arguments.action == 'remove'):
        del datafile[arguments.uuid][arguments.type]
        del datafile[arguments.uuid][arguments.type + "-hostname"]
        if len(datafile[arguments.uuid])== 0:
            del datafile[arguments.uuid]

    if (arguments.action == 'get'):
        if arguments.type in datafile[arguments.uuid]:
            print datafile[arguments.uuid][arguments.type]
            sys.exit(0)
    	else:
            print 'Key not found'
            sys.exit(1)

    print json.dumps(datafile, indent=4, sort_keys=True)
    #sys.exit(0)
    json_file = open(confFile,'w')
    json_file.write(json.dumps(datafile, indent=4, sort_keys=True))
    json_file.close() 
