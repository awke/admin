a:32:{i:0;a:3:{i:0;s:14:"document_start";i:1;a:0:{}i:2;i:0;}i:1;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:0;}i:2;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:4:"All,";}i:2;i:1;}i:3;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:5;}i:4;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:5;}i:5;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:110:"Just had Sue Groom call me with a problem that here mail client got
disconnected when trying to get new mail (";}i:2;i:7;}i:6;a:3:{i:0;s:7:"acronym";i:1;a:1:{i:0;s:4:"POP3";}i:2;i:117;}i:7;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:44:").  I had a look in the log
file and it says";}i:2;i:121;}i:8;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:165;}i:9;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:165;}i:10;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:105:"dovecot: pop3(sue@art2murals.com): Error: Message ordering changed
unexpectedly (msg #1: storage seq 666 ";}i:2;i:167;}i:11;a:3:{i:0;s:6:"entity";i:1;a:1:{i:0;s:2:"->";}i:2;i:272;}i:12;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:59:" 1)
dovecot: pop3(sue@art2murals.com): Fatal: Can't finish ";}i:2;i:274;}i:13;a:3:{i:0;s:7:"acronym";i:1;a:1:{i:0;s:4:"POP3";}i:2;i:333;}i:14;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:13:" UIDL command";}i:2;i:337;}i:15;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:350;}i:16;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:350;}i:17;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:153:"the quick and dirty fix is to delete the dovecot-uidlist file from every
subfolder including index but it will force the client to redownload the
mailbox";}i:2;i:353;}i:18;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:506;}i:19;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:506;}i:20;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:194:"I think in sues case its because the imap & pop3 uid lists are so
different in date when merged it wasnt happy.  (courier used separate
lists for impa &pop3 but dovecot seems to use a combined).";}i:2;i:508;}i:21;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:702;}i:22;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:702;}i:23;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:50:"The email is now in /Data/email/<DOMAIN>/<ACCOUNT>";}i:2;i:704;}i:24;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:754;}i:25;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:754;}i:26;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:84:"cd to this location and type find . -iname dovecot-uidlist you can then
delete them.";}i:2;i:756;}i:27;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:840;}i:28;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:840;}i:29;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:5:"Simon";}i:2;i:842;}i:30;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:847;}i:31;a:3:{i:0;s:12:"document_end";i:1;a:0:{}i:2;i:847;}}