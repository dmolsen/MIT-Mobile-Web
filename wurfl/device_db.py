#!/usr/bin/python
#
# Copyright (c) 2008 Massachusetts Institute of Technology
# 
# Licensed under the MIT License
# Redistributions of files must retain the above copyright notice.
# 
#

import bsddb
import sys
import time

def get_stable(name=None, mode='r'):
    if name is None:
        name = "stable_devices.db"
    return bsddb.hashopen(name, mode)

def make_py(filename):
    fp = open(filename, 'w')
    fp.write("devices = {\n")
    for user_agent, type in get_stable().items():
        fp.write('    "' + user_agent +'":\n        "' + type + '",\n\n')
    fp.write("}\n")
    fp.close()
        
def add_py(mod_name):
    the_module = __import__(mod_name)
    stable = get_stable(mode='w')
    for user_agent, type in the_module.devices.items():
         stable[user_agent]  = type
    stable.close()

def display(name):
    for user_agent, type in get_stable(name).items():
        print user_agent
        print type
        print ' '

def test():
    import wurfl_classify
    devices = get_stable()
    total = len(devices)
    failures = 0
    for user_agent, type in devices.items():
        classified = wurfl_classify.device_type(user_agent)
        if classified != type:
            print 'user_agent: "' + user_agent + '"'
            print 'correct type=' + type
            print 'classified=' + classified
            print ' '
            failures += 1
    print 'success %i/%i' % (total-failures, total)
    devices.close()

def merge(new_devices):
    stable = get_stable(mode='w')
    if new_devices:
        new_devices = bsddb.hashopen(new_devices,'r')
        for user_agent, type in new_devices.items():
            if not stable.has_key(user_agent):
                stable[user_agent] = type
        new_devices.close()
    stable.close()
    
def edit():
    devices = get_stable(mode='w')
    for user_agent, type in devices.items():
        response = menu(user_agent, type)
        if response:
            devices[user_agent] = menu_dict[response]
    devices.close()

def menu(user_agent, type):
    while True:
        print ''
        print '------------------------------------------------------------------'
        print user_agent
        print 'type=' + type
        answer = raw_input(menu_text)
        if answer in ('', '1', '2', '3', '4', '5'):
            return answer
        else:
            print invalid_response
            time.sleep(0.5)
            
menu_text = """
1) computer
2) iPhone
3) smart phone
4) feature phone
5) crawler/spider
Press Enter to leave unchanged: """

menu_dict = {
    '1': 'computer',
    '2': 'iphone',
    '3': 'smart_phone',
    '4': 'feature_phone',
    '5': 'spider',
}

invalid_response = """
Invalid response!!!
Try again"""

usage_string = """
To edit:
./device_db.py edit

To test classification code:
./device_db.py test

To merge new devices database:
./device_db.py merge filename

To view device database:
./device_db.py display

To create an editable python file with database data
./device_db.py makepy data.py

To add data from a python file to the database data
./device_db.py addpy data
"""

def usage():
    print usage_string

def second_arg():
    if len(sys.argv) > 2:
        return sys.argv[2]

if len(sys.argv) == 1:
    usage()
elif sys.argv[1] == 'test':
    test()
elif sys.argv[1] == 'edit':
    edit()
elif sys.argv[1] == 'display':
    display(second_arg())
elif sys.argv[1] == 'makepy':
    make_py(sys.argv[2])
elif sys.argv[1] == 'addpy':
    add_py(sys.argv[2])
elif sys.argv[1] == 'merge':
    try:
        new_devices = sys.argv[2]
    except IndexError:
        new_devices = ''   
    merge(new_devices)
else:
    usage()
