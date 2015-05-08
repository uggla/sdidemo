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


# Variable needed

confFile = "/home/sdi/ansible/datafile.json"
prog = sys.argv[0]

if __name__ == '__main__':
    pp = pprint.PrettyPrinter(indent=4)

    parser = argparse.ArgumentParser(version = prog + ' version 1.0')
    
    parser.add_argument('action', action='store',
                        choices=['add','remove'],
                    help='Action to do either add or remove storage')

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

    if (arguments.action == "add"):
        if datafile.has_key(arguments.uuid):
            datafile[arguments.uuid].append({arguments.type: arguments.ipaddress} )
        else:
            datafile[arguments.uuid]=[{arguments.type: arguments.ipaddress}]

    if (arguments.action == "remove"):
        item = datafile[arguments.uuid].index({arguments.type: arguments.ipaddress})
        del datafile[arguments.uuid][item]
        if len(datafile[arguments.uuid])== 0:
            del datafile[arguments.uuid]


    print json.dumps(datafile, indent=4, sort_keys=True)
    #sys.exit(0)
    json_file = open(confFile,'w')
    json_file.write(json.dumps(datafile, indent=4, sort_keys=True))
    json_file.close() 
