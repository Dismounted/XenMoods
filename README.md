XenMoods
====

XenMoods is an add-on for XenForo that allows users to set their current mood.

Please read [Shelley's helpful guide](http://xenforo.com/community/threads/xenmood-guide.9773/) if your moods do not show up after installing.

Installation
----

1. To begin, upload all the files in the *upload/* directory into your XenForo base directory (the one with *library/* and *styles/*).
2. Next, go into your Admin Control Panel, and click *Install Add-on*.
3. Click the *+ Install Add-on* button.
4. Select *addon_xenmoods.xml* as the file to upload.
5. Click *Install Add-on* to confirm the installation of XenMoods.
6. Perform the template edits as below.

Display Locations
----

Moods are currently displayed in four locations which can be turned on or off through Style Properties (property location in parenthesis):
- Sidebar Visitor Panel (XenMoods, Show User Mood on Sidebar)
- Thread View (XenMoods, Show User Mood on Thread View)
- Member Card (XenMoods, Show User Mood on Member Card)
- Member Profile (XenMoods, Show User Mood on Profile)

Adding, Editing and Deleting Moods
----

The manager is located at *admin.php?moods/*. The link can be found in the left navigation bar when on the Admin Control Panel homepage.

The interface should be intuitive enough to work out. Enter image URLs as relative, e.g. *styles/default/xenmoods/happy.png*.

NB. You must have the Admin Permission *Manage moods* ticked.

User Permissions
----

You can set permissions for specific user groups as to whether they can see and have moods. The two permissions are *View moods* and *Have moods*.

Upgrading
----

1. Upload all the XenMoods files, overwriting any old ones.
2. Next, go to your Admin Control Panel homepage, and click *List Add-ons* or *Manage Add-ons*.
3. Activate the *Controls* drop-down for XenMoods, and click *Upgrade*.
4. Select *addon_xenmoods.xml* as the file to upload.
5. Click *Upgrade Add-on* to confirm.
6. If you are upgrading from pre-1.1.2, remove the template edits made when installing.

Uninstallation
----

If, for any reason, you would like to uninstall XenMoods, the following steps are necessary:
1. Undo the template edits that were performed on installation.
2. Go to your Admin Control Panel homepage, and click *List Add-ons* or *Manage Add-ons*.
3. Activate the *Controls* drop-down for XenMoods, and click *Uninstall*.
4. Remove all the files from XenForo (*library/XenMoods/* and *styles/default/xenmoods/*).