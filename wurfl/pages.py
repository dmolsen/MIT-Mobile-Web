#
# Copyright (c) 2008 Massachusetts Institute of Technology
# 
# Licensed under the MIT License
# Redistributions of files must retain the above copyright notice.
# 
#

register = (
"""<html>
     <title>
          register your device
     </title>
   <body>
         your device has user_agent string: "%(user_agent)s" <br />
         your device is classfied as %(device_type)s <br />
         <form method="GET">
              change your device:
              <select name="device_type">
                   <option value="computer">computer</option>
                   <option value="smart_phone">smart phone</option>
                   <option value="feature_phone">feature phone</option>
                   <option value="iphone">iPhone</option>
              </select>
              <input type="submit" value="change"/>
         </form>
    </body>
</html>"""
)


device = (
"""<html>
     <title>
         your device info
     </title>
   <body>
         your device has user_agent string: "%(user_agent)s" <br />
         your device is classfied as %(device_type)s <br />
   </body>
</html>"""
)
