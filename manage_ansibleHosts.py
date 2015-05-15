#!/usr/bin/python

'''
Update ansibleHosts to register/unregister hosts with ansible

@author : rene.ribaud@hp.com
'''
import sys
import pprint
import argparse
import os
import ConfigParser

# Variable needed

confFile = "/home/sdi/ansible/ansibleHosts"
prog = sys.argv[0]

if __name__ == '__main__':
    pp = pprint.PrettyPrinter(indent=4)

    parser = argparse.ArgumentParser(version = prog + ' version 1.0')
    
    parser.add_argument('action', action='store',
                        choices=['add','remove'],
                    help='Action to do either add or remove hostname to ansibleHosts')

    parser.add_argument('group', action='store',
                    help='Group to add or remove the hostname')
    
    parser.add_argument('hostname', action='store',
                    help='hostname to add or remove')

    arguments = parser.parse_args()
    
    # Load json

    config = ConfigParser.RawConfigParser(allow_no_value=True)
    config.read(confFile)
    
    if (arguments.action == "add"):
        config.add_section(arguments.group)
        config.set(arguments.group, arguments.hostname)
    
    if (arguments.action == "remove"):
        config.remove_option(arguments.group, arguments.hostname)

    with open(confFile, 'wb') as configfile:
        config.write(configfile)
