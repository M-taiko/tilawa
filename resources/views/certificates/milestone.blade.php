<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>شهادة إنجاز</title>
 <style>
 @page {
 margin: 0;
 }
 body {
 font-family: 'DejaVu Sans', sans-serif;
 direction: rtl;
 text-align: center;
 margin: 0;
 padding: 40px;
 background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
 min-height: 100vh;
 }
 .certificate {
 background: white;
 padding: 60px;
 border-radius: 20px;
 box-shadow: 0 20px 60px rgba(0,0,0,0.3);
 max-width: 800px;
 margin: 0 auto;
 }
 .header {
 border-bottom: 3px solid #f5576c;
 padding-bottom: 30px;
 margin-bottom: 40px;
 }
 .logo {
 font-size: 48px;
 margin-bottom: 10px;
 }
 .title {
 font-size: 36px;
 font-weight: bold;
 color: #333;
 margin-bottom: 10px;
 }
 .subtitle {
 font-size: 20px;
 color: #666;
 }
 .content {
 padding: 40px 0;
 }
 .award-text {
 font-size: 18px;
 color: #666;
 margin-bottom: 30px;
 }
 .student-name {
 font-size: 42px;
 font-weight: bold;
 color: #f5576c;
 margin: 30px 0;
 padding: 20px;
 border-top: 2px solid #eee;
 border-bottom: 2px solid #eee;
 }
 .achievement {
 font-size: 32px;
 font-weight: bold;
 color: #f093fb;
 margin: 30px 0;
 }
 .description {
 font-size: 18px;
 color: #666;
 line-height: 1.8;
 margin: 30px 0;
 }
 .footer {
 margin-top: 60px;
 padding-top: 30px;
 border-top: 2px solid #eee;
 }
 .date {
 font-size: 14px;
 color: #666;
 margin-top: 20px;
 }
 .seal {
 width: 100px;
 height: 100px;
 border: 3px solid #f5576c;
 border-radius: 50%;
 display: flex;
 align-items: center;
 justify-content: center;
 margin: 20px auto;
 font-size: 12px;
 color: #f5576c;
 font-weight: bold;
 }
 </style>
</head>
<body>
 <div class="certificate">
 <div class="header">
 <div class="logo">⭐</div>
 <div class="title">{{ $tenant->name }}</div>
 <div class="subtitle">شهادة تقدير وإنجاز</div>
 </div>

 <div class="content">
 <div class="award-text">يسر إدارة المركز أن تمنح هذه الشهادة للطالب / الطالبة</div>

 <div class="student-name">{{ $student->name }}</div>

 <div class="achievement">
 لإتمام حفظ {{ $juz_count }} جزء من القرآن الكريم
 </div>

 <div class="description">
 تقديراً لجهوده المبذولة وإتقانه للحفظ<br>
 نسأل الله أن يزيده علماً وتوفيقاً<br>
 وأن يجعل القرآن ربيع قلبه ونور صدره
 </div>

 <div class="seal">
 ختم المركز
 </div>
 </div>

 <div class="footer">
 <div class="date">التاريخ الهجري: {{ $hijri_date }}</div>
 <div class="date">التاريخ الميلادي: {{ $date }}</div>
 </div>
 </div>
</body>
</html>
