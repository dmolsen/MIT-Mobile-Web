#
# Copyright (c) 2008 Massachusetts Institute of Technology
# 
# Licensed under the MIT License
# Redistributions of files must retain the above copyright notice.
# 
#

# these paths need to set correctly
wurfl_url = 'wurfl'
wurfl_path = '/apache/htdocs/MIT-Mobile-Web/wurfl'

from mod_python import apache
import bsddb
import re
import pages
import cgi
# we import this only when needed to save memory
# import wurfl_classify

stable_db = bsddb.hashopen(wurfl_path + '/stable_devices.db', 'r')

class DeviceCache:

    class NotFound(Exception):
        pass

    def __init__(self):
        self.db = bsddb.hashopen(wurfl_path + '/device_hash.db')

    def reset(self):
        self.db.close()
        self.__init__()

    def find(self, user_agent):
        if not self.db.has_key(user_agent):
            raise self.NotFound
        else:
            return self.db[user_agent]

    def add_device(self, user_agent, device_type):
        self.db[user_agent]  = device_type
        self.reset()

def handler(req):
    def fill(page_tpl):
        return page_tpl % {'user_agent': user_agent, 'device_type': device_type}

    device_cache = DeviceCache()

    if req.uri.startswith('/' + wurfl_url + '/api'):
        args = cgi.parse_qs(req.args)
        req.content_type = "text/plain"
        req.write(mobile_detect(args['UserAgent'][0], device_cache))
    else:
        user_agent = req.headers_in['User-Agent'] 
        device_type = mobile_detect(user_agent, device_cache)
        req.content_type = "text/html"        
        req.write(fill(pages.device))

    return apache.OK

def mobile_detect(user_agent, device_cache):

    wurfl_obj = None
    if stable_db.has_key(user_agent):
        return stable_db[user_agent]
    try:
        device = device_cache.find(user_agent)
    except device_cache.NotFound:
        try:
            device_cache.reset()
            device = device_cache.find(user_agent)
        except device_cache.NotFound:
            import wurfl_classify
            device = wurfl_classify.device_type(user_agent)
            device_cache.add_device(user_agent, device)

    return device    
