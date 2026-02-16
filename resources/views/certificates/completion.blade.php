<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>شهادة إتمام</title>
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
 background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
 border-bottom: 3px solid #667eea;
 padding-bottom: 30px;
 margin-bottom: 40px;
 }
 .logo {
 font-size: 48px;
 color: #667eea;
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
 color: #667eea;
 margin: 30px 0;
 padding: 20px;
 border-top: 2px solid #eee;
 border-bottom: 2px solid #eee;
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
 display: flex;
 justify-content: space-between;
 align-items: flex-start;
 }
 .signature-block {
 text-align: center;
 flex: 1;
 }
 .signature-line {
 border-top: 2px solid #333;
 width: 200px;
 margin: 10px auto;
 }
 .signature-label {
 font-size: 14px;
 color: #666;
 margin-top: 5px;
 }
 .date {
 font-size: 14px;
 color: #666;
 }
 .seal {
 width: 100px;
 height: 100px;
 border: 3px solid #667eea;
 border-radius: 50%;
 display: flex;
 align-items: center;
 justify-content: center;
 margin: 0 auto;
 font-size: 12px;
 color: #667eea;
 font-weight: bold;
 }
 </style>
</head>
<body>
 <div class="certificate">
 <div class="header">
 <div class="logo">🕌</div>
 <div class="title">{{ $tenant->name }}</div>
 <div class="subtitle">مركز تحفيظ القرآن الكريم</div>
 </div>

 <div class="content">
 <div class="award-text">تشهد إدارة المركز بأن الطالب / الطالبة</div>

 <div class="student-name">{{ $student->name }}</div>

 <div class="description">
 قد أتم / أتمت حفظ كتاب الله العزيز كاملاً<br>
 متقناً للتلاوة والتجويد<br>
 فنسأل الله تعالى أن يجعله من حملة كتابه العاملين به<br>
 وأن ينفع به الإسلام والمسلمين
 </div>

 <div class="seal">
 ختم المركز
 </div>
 </div>

 <div class="footer">
 <div class="signature-block">
 <div class="signature-line"></div>
 <div class="signature-label">مدير المركز</div>
 </div>

 <div style="text-align: center; flex: 1;">
 <div class="date">التاريخ الهجري: {{ $hijri_date }}</div>
 <div class="date">التاريخ الميلادي: {{ $date }}</div>
 </div>

 <div class="signature-block">
 <div class="signature-line"></div>
 <div class="signature-label">المعلم المشرف</div>
 </div>
 </div>
 </div>
</body>
</html>
