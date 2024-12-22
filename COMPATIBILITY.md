# eZ Publish Basic Module Compatibility with PHP8.x

# Note: This document and related code are in active development and testing.

## User Modules

- ezuser
    - addressedit
    - forgot
    - forgotmessage
    - login
    - missingmailmessage
    - norights
    - userbox
    - usercheck
    - useredit
    - userwithaddress
- ezurltranslator
    - None (Admin Only)
- ezsitemanager
    - static - undocumented static content view.
- ezstats
    - storestats
- ezsysinfo
    - No user module views. Only Admin module views.
- ezstats
    - overview
    - entryexitreport
    - pageviewlist/last/20
    - visitorlist/top/20
    - refererlist/top/20
    - browserlist/top/25
    - requestpagelist/top/20
    - yearreport
    - monthreport
    - dayreport
- ezquiz
    - menubox
    - quizlist
    - quizmyscores
    - quizopen
    - quizplay
    - quizscores
- ezbulkmail
    - bulklist
    - categoryedit
    - cron
    - mailview
    - menubox
    - singlelist
    - subscriptionlist
    - subscriptionlogin
    - usermessages
- ezform
    - formview
- ezadddress
    - No user modules views. Admin only.
- ezmediacatalogue
    - No user module views. Only Admin module views.
- ezmessage
    - No user module views. Only Admin module views.
- ezmodule
    - No user module views. Only Admin module views.
- ezimagecatalogue
    - categoryedit
    - customimage
    - filedownload
    - imageedit
    - imagelist
    - imageview
    - menubox
    - menucategorylist
    - searchsupplier
    - slideshow
- ezfilemanager
    - filedownload
    - filelist
    - fileupload
    - fileview
    - folderedit
    - menubox
    - menufilelist
    - menufolderlist
    - search
    - viewfile
- ezcalendar
    - appointmentedit
    - appointmentview
    - dayview
    - monthview
    - trustees
    - yearview
- eztodo
    - menubox
    - todoedit
    - todoinfo
    - todolist
    - todomenulist
    - todoview
- ezcontact
    - companysearch
    - consultationlist
    - menubox
    - personsearch
    - searchsupplier
    - urlsupplier
- ezerror
    - Built-In - Kernel Error Handling Default User Datasupplier Based Module View (Dynamically Driven From ezerror/admin/datasupplier.php include instead of implementation).
- ezexample
    - listtable
    - page
- ezsearch
    - menubox
    - search
- ezsession
    - No user module views. Admin only.
- ezbug
    - bugslist
    - bugreport
    - bugview
    - fileedit
    - imageedit
    - menubox
    - menumodulelist
    - reportsuccess
    - search
    - unhandledbugs
- eznewsfeed
    - allcategories
    - headlines
    - menubox
    - newslist
    - search
- ezabout
    - about - One Datasupplier based module view about eZ Publish Basic Website Installation / Project CMS. Available via /about Relative URL.
- ezad
    - adlist
    - gotoadd
    - queuedadlist
- ezpoll
    - pollist
    - result
    - userlogin
    - vote
    - votebox
    - votepage
- ezlink
    - gotolink
    - latest
    - linkcategorylist
    - menubox
    - onepagelinklist
    - search
    - success
    - suggestlink
- ezforum
    - categorylist
    - forumlist
    - latestmessages
    - menubox
    - menuforumlist
    - message
    - messagebody
    - messageedit
    - messageform
    - messagelist
    - messagelistflat
    - messagepath
    - messagepermissions
    - messagereply
    - messagesearch
    - messagesimplelist
    - search
    - searchsupplier
    - userlogin
- ezmail
    - accountedit
    - configure
    - fileedit
    - folderlist
    - link
    - mailedit
    - maillist
    - mailview
    - menubox
    - search
- eztrade
    - cart - Needs work. Cart Prices are all Zeros.
    - categorylist
    - categorytreelist
    - checkout - Fatal error on page load.
    - confirmation
    - customerlogin
    - extendedsearch
    - findwishlist
    - hostdealslist
    - invoice
    - mastercard
    - menubox
    - metasupplier
    - orderlist
    - ordersendt
    - orderview
    - payment
    - paypalnotify
    - precheckout
    - productlist
    - productsearch
    - productview
    - searchsupplier
    - sendwishlist
    - smallcart
    - smallproductlist
    - viewwishlist
    - visa
    - voucher
    - voucherinformation
    - vouchermain
    - voucherview
    - wishlist
- ezarticle
    - articleedit
    - articleheaderlist
    - articlelinks
    - articlelist
    - articlelistrss
    - articleview
    - authorlist
    - authorview
    - extendedsearch
    - fileedit
    - filelist
    - frontpage
    - headlines
    - imageedit
    - imagelist
    - mailtofriend
    - menuarticleview
    - menumaker
    - newsgroup
    - search
    - searchform
    - searchsupplier
    - sitemap
    - smallarticlelist
    - topiclist
    - urlsupplier

## Admin Modules

- ezuser
    - userlist -  Tests as 100% Functional.
    - grouplist - Tests as OK but with 1 - 2 missing icons.
    - useredit/new - Tests as 100% Functional.
    - groupedit/new - Tests as OK but with 7 - 10 warnings.
    - authorlist - Tests as 100% Functional.
    - extsearch - Tests as 100% Functional.
    - sessioninfo - Needs work. Fatal Errors on Page Load.
    - passwordchange - Needs work. Usage error as screen is blank and no form to change password is displayed from admin header link to change password.
- ezsitemanager
    - section/list - Tests as Loading Page OK.
    - cache - Tests as OK but with 1 - 2 warnings.
    - file/list - Tests as Loading Page OK.
    - template/list - Tests as 100% Functional.
    - siteconfig - Tests as OK but with 1 - 2 warnings.
    - menu/list - Tests as Loading Page OK.
    - sqladmin/query - Tests as Loading Page OK. (Non Functional) Fatal Errors On Usage.
- ezsysinfo
    - sysinfo - Tests as Loading Page OK. Verify System Information Valid In Greater Detail.
    - netinfo - Tests as 100% Functional.
    - hwinfo - Tests as 100% Functional.
    - meminfo - Tests as 99% Functional. One critical template system problem requireing refactoring to solve a array to string conversion (Warning: Array to string conversion in /home/ez/public_html/classes/eztemplate.php on line 618).
    - fileinfo - Tests as 100% Functional.
- ezstats
    - overview - Needs work. Fatal Errors on Page Load.
    - entryexitreport - Needs work. Empty template variables on page and no stats.
    - pageviewlist/last/20 - Needs work. Fatal Errors on Page Load.
    - visitorlist/top/20 - Needs work. Fatal Errors on Page Load.
    - refererlist/top/20/ - Needs work. Fatal Errors on Page Load.
    - browserlist/top/25/ - Needs work. Fatal Errors on Page Load.
    - requestpagelist/top/20/ - Needs work. Fatal Errors on Page Load.
    - yearreport - Needs work. Fatal Errors on Page Load.
    - monthreport - Needs work. Empty template variables on page and no stats.
    - dayreport - Needs work. Fatal Errors on Page Load.
- ezquiz
    - game/list - Tests as 100% Functional.
    - game/new - Tests as 100% Functional. 
- ezmessage
    - list - Tests as 100% Functional.
    - edit - Tests as 100% Functional.
- ezbulkmail - This admin module views all require real usage refactoring on actions per view.
    - categorylist - Tests as Loading Page OK. 
    - templatelist - Tests as Loading Page OK.
    - drafts - Tests as Loading Page OK.
    - mailedit - Tests as Loading Page OK.
    - templateedit - Tests as Loading Page OK.
    - masssubscribe - Tests as Loading Page OK.
    - userlist - Tests as Loading Page OK. No users displayed by default possible bug.
- ezform
    - form/list - Tests as 100% Functional.
    - form/new - Tests as 100% Functional.
- ezadddress
    - phonetype/list - Tests as 100% Functional.
    - addresstype/list - Tests as 100% Functional.
    - onlinetype/list - Tests as 100% Functional.
    - country/list/ - Tests as 100% Functional.
- ezmediacatalogue
    - category/list - Tests as 100% Functional.
    - category/new - Tests as 100% Functional.
    - media/new - Tests as 100% Functional.
    - unassigned - Tests as 100% Functional.
    - typelist - Tests as 100% Functional.
    - typeedit - Tests as 100% Functional.
- ezimagecatalogue
    - image/list - Tests as 100% Functional.
    - category/new - Tests as 100% Functional.
    - image/new - Tests as 100% Functional.
    - unassigned - Tests as 100% Functional.
- ezfilemanager
    - list - Tests as 100% Functional.
    - folder/new - Tests as 100% Functional.
    - new - Tests as 100% Functional.
    - unassigned - Tests as 100% Functional.
- ezcalendar
    - typelist - Tests as 100% Functional.
    - typeedit/new - Tests as 100% Functional.
- eztodo
    - categorytypelist - Tests as 100% Functional.
    - prioritytypelist - Tests as 100% Functional.
    - statustypelist - Tests as 100% Functional.
- ezcontact
    - company/list - Tests as 80% Functional. Some Warnings on Page Load.
    - person/list - Needs work. Edit buttons all trigger fatal user form load and store Fatal errors.
    - consultation/list -  Needs work. Edit buttons all trigger fatal user form load and store Fatal errors.
    - company/new - Needs work. Massive warnings blocking display.
    - person/new - Needs work. Massive warnings blocking display.
    - projecttype/list - Tests as 80% Functional. Some Warnings on Page Load. Does not store fatal error on form submission.
    - consultationtype/list - Needs work. Fatal Errors on Page Load.
- ezbug
    - unhandled - Tests as 100% Functional.
    - archive - Tests as 100% Functional.
    - priority/edit - Tests as 100% Functional.
    - priority/list - Tests as 100% Functional.
    - category/list - Tests as 100% Functional.
    - module/list - Tests as 100% Functional.
    - status/list - Tests as 100% Functional.
- eznewsfeed
    - unpublished - Tests as 100% Functional.
    - archive - Tests as 100% Functional.
    - news/new - Tests as 100% Functional.
    - category/new - Tests as 100% Functional.
    - sourcesite/new - Tests as 100% Functional.
    - importnews - Tests as 100% Functional.
- ezad
    - archive - Tests as 100% Functional.
    - category/new - Tests as 100% Functional.
    - ad/new - Tests as 100% Functional.
- ezpoll
    - pollist - Tests as 100% Functional.
    - polledit/new - Tests as 100% Functional.
- ezlink
    - category/0 - Tests as 100% Functional.
    - categoryedit/edit/1 - Tests as 100% Functional.
    - unacceptedlist -  Tests as 100% Functional.
    - typelist - Tests as 100% Functional.
    - typeedit - Tests as 100% Functional.
    - categoryedit/new -Tests as 100% Functional.
    - linkedit/new - Tests as 100% Functional.
- ezforum
    - unapprovedlist - Tests as 100% Functional. No errors but no listing.
    - categorylist/ - Tests as 100% Functional. No errors but no listing.
    - categoryedit/new - Needs work. Warnings blocking form.
    - forumedit/new - Needs work. Fatal errors on page load.
- eztrade
    - orderlist - Needs work. Fatal errors on page load.
    - customerlist - Needs work. Fatal errors on page load.
    - categorylist - Needs work. Fatal errors on page load.
    - typelist -  Tests as 100% Functional.
    - vattypes -  Needs work. Warnings blocking form.
    - shippingtypes - Needs work. Warnings blocking form.
    - currency - Needs work. Warnings blocking form.
    - pricegroups/list - Needs work. Fatal errors on page load.
    - categoryedit - Needs work. Warnings blocking form.
    - typeedit - Needs work. Fatal errors on page load.
    - productedit - Needs work. Fatal errors on page load.
    - voucher - Needs work. Fatal errors on page load.
    - voucherlist - Needs work. Fatal errors on page load.
- ezarticle
    - archive - Tests as 100% Functional.
    - article/search - Tests as 100% Functional.
    - unpublished - Tests as 100% Functional.
    - pendinglist - Tests as 100% Functional.
    - sitemap - Tests as 100% Functional.
    - topiclist - Tests as 100% Functional.
    - type/list - Tests as 100% Functional.
    - type/edit - Tests as 100% Functional.
    - categoryedit/new - Tests as 100% Functional.
    - articleedit/new - Tests as 100% Functional.
    - search - Tests as 100% Functional.

Note: Admin password change menu feature does not function as desired.

Note: Printable admin view features currently has fatal errors and distracting warnings.

## Admin Module Default Views That Test OK Based On Actual Use

- ezlink
- ezurltranslator
- ezuser
- ezsysinfo
- ezquiz
- ezmessage
- ezform
- ezmediacatalogue
- ezimagecatalogue
- ezfilemanager
- ezcalendar
- eztodo
- ezbug
- eznewsfeed
- ezad
- ezpoll
- ezarticle



### Mostly Finished

- ezlink
- ezurltranslator
- ezuser
    - userlist -  Tests as 100% Functional.
    - useredit/new - Tests as 100% Functional.
    - authorlist - Tests as 100% Functional.
    - extsearch - Tests as 100% Functional.
- ezsysinfo - the stats were variable on our vm so this may need improvement on the actual data displayed.
    - sysinfo - Tests as Loading Page OK. Verify System Information Valid In Greater Detail.
    - netinfo - Tests as 100% Functional.
    - hwinfo - Tests as 100% Functional.
    - fileinfo - Tests as 100% Functional.

## Admin Module Menu Views That Test OK

- ezurltranslator
    - urledit - Tests as 100% Functional.

## Default Module Menu Views That Need Further Development

The following modules need work to be considered complete, tested and working correctly. Currently we rate these as only 75% complete.

- ezcontact
- ezcalendar
- ezbulkmail
- ezmail
- ezstats

## User Module Menu View URIs

Module: ezarticle
Views:
/article/frontpage/1
/article/frontpage/2
/article/frontpage/3
/article/frontpage/4

## Addendum


- /article/archive/0
- /article/archive/1
- /article/archive/6
- /article/author/list
- /article/author/view/1
- /article/static/23/1/1
- /article/view/23/1/1
- /article/sitemap
- /article/index
- /article/articleedit/new
- /article/search
- /article/articleprint/21/-1/1
- /article/mailtofriend/21/1/1
-


- /forum/categorylist
- /forum/forumlist/1
- /forum/message/23
- /forum/search/
-

- /trade/orderlist
- /trade/orderview/7
- /trade/voucherview
- /trade/findwishlist
- /trade/sendwishlist
- /trade/wishlist
- /trade/cart
- /trade/productlist/1
- /trade/productlist/0
- /trade/productview/3
- /trade/search/

- /imagecatalogue/image/list/0
- /imagecatalogue/image/list/1
- /imagecatalogue/image/new
- /imagecatalogue/category/new

- /link/category/0
- /link/category/1
- /link/category/2
- /link/gotolink/addhit/2/?Url=slashdot.org
- /link/suggestlink

- /poll/polls
- /poll/votepage/1
- /poll/result/1?

- /user/login
- /user/logout
- /user/userwithaddress/edit//1/

- /bulkmail/login/

- /newsfeed/allcategories/
- /newsfeed/latest/1

- /search/?SectionIDOverride=1&SearchText=ez&Search=Search

- /calendar/monthview
- /calendar/yearview/2024
- /calendar/monthview/2024/12
- /calendar/dayview/2024/12
- /calendar/dayview/2024/12/18
- /calendar/appointmentedit/new
- /calendar/trustees

- /todo
- /todo/todoedit/new

- /mail/folderlist
- /mail/folder/1
- /mail/folder/4
- /mail/config
- /mail/folderedit

- /filemanager/list
- /filemanager/list/1
- /filemanager/fileview/1/

- /contact/company/list
- /contact/person/list
- /contact/company/new
- /contact/person/new
- /contact/consultation/list

- /bug/archive
- /bug/archive/0
- /bug/archive/1
- /bug/bugview/1/
- /bug/edit/edit/1
- /bug/unhandled
- /bug/report/create

- /todo
- /todo/todolist
- /todo/todoedit/new

- /calendar/monthview
- /calendar/monthview/2024/12
- /calendar/yearview/2024
- /calendar/dayview/2024/12/19

## Admin Module Menu View URIs

- /article/archive
- /article/archive/0
- /article/archive/1
- /article/articlepreview/3


- /trade/orderlist
- /trade/orderedit/1
- /trade/customerlist
- /trade/customerview/2
- /trade/productedit/edit/1
- /trade/voucher/edit/3
- /trade/categorylist
- /trade/categorylist/parent/1
- /trade/productedit/productpreview/3
- /trade/typelist
- /trade/vattypes
- /trade/shippingtypes
- /trade/currency
- /trade/pricegroups/list
- /trade/categoryedit
- /trade/typeedit
- /trade/productedit
- /trade/voucher
- /trade/voucherlist

-
-

- /forum/unapprovedlist
- /forum/categorylist
- /forum/forumlist/1
- /forum/messagelist/2
- /forum/message/27
- /forum/messageedit/edit/27
- /forum/categoryedit/new/
- /forum/forumedit/new/

- /ad/archive
- /ad/archive/1
- /ad/statistics/1
- /ad/category/new/
- /ad/ad/new/


- /form/form/list/
- /form/form/new/
- /form/form/edit/2/
- /form/form/preview/2/