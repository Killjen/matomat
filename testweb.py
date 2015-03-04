#!/usr/bin/python
import time
import gtk
import webkit
import gobject
import thread
import requests
#https://ardoris.wordpress.com/2009/04/26/a-browser-in-14-lines-using-python-and-webkit/

changed = False
lock = thread.allocate_lock()

class Browser(gtk.Window):
    def __init__(self):
        gtk.Window.__init__(self)
        gobject.threads_init()
        self.fullscreen()

        self.browser= webkit.WebView()
        self.add(self.browser)
        self.browser.load_uri("file:///home/amos/Documents/robotikfp/matomat/www/currentpage.html")
        self.show_all()

        gobject.timeout_add(500, self.update)
        

    def update(self):
        global changed
        if changed:
            lock.acquire()
            changed = False
            lock.release()
            self.browser.load_uri("file:///home/amos/Documents/robotikfp/matomat/www/currentpage.html")
            self.fullscreen()
        return True


def getThread():        #Thread listens for RFID input and gets respective webpage
    global changed
    while True:
        rsp = "FIRST"
        fo = open("www/currentpage.html", "wb")
        fo.write(rsp);
        fo.close()
        lock.acquire()
        changed = True
        lock.release()

        time.sleep(10)
        print "done"
        s = requests.Session()
        rsp = s.post("http://localhost/pythonopen.php", data={"id": "91242149124"}, verify=False)
        rsp = rsp.text

        fo = open("www/currentpage.html", "wb")
        fo.write(rsp);
        fo.close()

        lock.acquire()
        changed = True
        lock.release()
        time.sleep(10)

thread.start_new_thread(getThread, ())
win = Browser()
gtk.main()