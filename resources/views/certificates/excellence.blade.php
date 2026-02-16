<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>شهادة تفوق</title>
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
 background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
 min-height: 100vh;
 }
 .certificate {
 background: white;
 padding: 60px;
 border-radius: 20px;
 box-shadow: 0 20px 60px rgba(0,0,0,0.3);
 max-width: 800px;
 margin: 0 auto;
 border: 5px solid #fcb69f;
 }
 .header {
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
 font-size: 24px;
 color: #fcb69f;
 font-weight: bold;
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
 color: #fcb69f;
 margin: 30px 0;
 padding: 20px;
 background: #ffecd2;
 border-radius: 10px;
 }
 .reason {
 font-size: 22px;
 color: #666;
 line-height: 1.8;
 margin: 30px 0;
 padding: 20px;
 background: #f9f9f9;
 border-radius: 10px;
 }
 .footer {
 margin-top: 60px;
 padding-top: 30px;
 border-top: 2px solid #fcb69f;
 }
 .date {
 font-size: 14px;
 color: #666;
 margin-top: 20px;
 }
 </style>
</head>
<body>
 <div class="certificate">
 <div class="header">
 <div class="logo">🏆</div>
 <div class="title">{{ $tenant->name }}</div>
 <div class="subtitle">شهادة تفوق وتميز</div>
 </div>

 <div class="content">
 <div class="award-text">تمنح هذه الشهادة للطالب / الطالبة المتميز</div>

 <div class="student-name">{{ $student->name }}</div>

 <div class="reason">
 {{ $reason }}
 </div>

 <div class="award-text">
 تقديراً لجهوده وتميزه وحسن أخلاقه<br>
 نسأل الله أن يوفقه لما يحب ويرضى
 </div>
 </div>

 <div class="footer">
 <div class="date">التاريخ الهجري: {{ $hijri_date }}</div>
 <div class="date">التاريخ الميلادي: {{ $date }}</div>
 </div>
 </div>
</body>
</html>
