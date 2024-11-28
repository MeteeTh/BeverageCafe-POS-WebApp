#โปรเจกต์เว็บงานที่ 1 ของชีวิต

"การพัฒนาฐานข้อมูลระบบการขายหน้าร้าน (POS) ANDY Coffee & Friends"

โปรเจกต์นี้พัฒนาขึ้นเมื่อชั้นปีที่ 2 ในชุดวิชา DA

ออกแบบร่วมกับ Adobe Dreamweaver CS5.5 ใช้ php sql เป็นระบบหลังบ้านเชื่อมฐานข้อมูล

โปรเจกต์เว็บนี้ไม่ได้ deploy หรือ hosting ไปใช้งานจริง เนื่องจากเป็นเพียง capstone project ในชุดวิชา ซึ่งผมไม่มีแพลนที่จะพัฒนาต่อ (อยากรื้อทำใหม่เลย เพราะโค้ดมั่วมาก)
และหากจะนำไปใช้จริงจะมีค่าใช้จ่ายและเว็บไซต์ค่อนข้างไม่สมบูรณ์ ไม่รองรับ responsive

=====================================================================================

***Require***
1. font Kanit หากไม่มีให้โหลดและติดตั้งก่อน (https://fonts.google.com/specimen/Kanit)

2. Appserv v 8.5.0 และ PHP Version 5.6 เท่านั้น (http://prdownloads.sourceforge.net/appserv/appserv-win32-8.5.0.exe?download)

3. Import ฐานข้อมูลที่ชื่อ andypos.sql ก่อนใช้งาน 
หากต้องการลบข้อมูลทั้งหมด เพื่อใช้ข้อมูลใหม่ที่ท่านต้องการ
ใช้คำสั่ง DELETE FROM table_name; หรือ TRUNCATE TABLE table_name; โดยให้ลบตารางตามลำดับดังนี้
1) receiptdetail
2) receipt
3) menulist
4) menutype
(table_name หมายถึง ชื่อตารางที่ต้องการลบ)

4. คู่มือการใช้งานโปรดดูที่ Final Report หน้าที่ 22-33

*การเข้าสู่ระบบ*
Username : AndyAdmin
Password : password

*หมายเหตุ 1 : เว็บไซต์รองรับเฉพาะ PC/Laptop
*หมายเหตุ 2 : บางหน้าในเว็บไซต์มี Sound effect
