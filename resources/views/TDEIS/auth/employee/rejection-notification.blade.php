<!DOCTYPE html>
<html>
<head>
    <title>Assignment Rejection Notification</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin: 20px 0; }
        .footer { margin-top: 50px; font-size: 12px; text-align: center; }
        .signature { margin-top: 50px; }
        .company-name { font-size: 24px; font-weight: bold; }
        .document-title { font-size: 18px; margin: 10px 0; }
        .date { text-align: right; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">TDEIS</div>
        <div class="document-title">Project Assignment Rejection Notification</div>
    </div>

    <div class="date">
        Date: {{ now()->format('Y-m-d') }}
    </div>

    <div class="content">
        <p>Dear {{ $assignment->user->name }},</p>

        <p>We regret to inform you that your assignment to the project <strong>{{ $assignment->project->name }}</strong>
        for the skill <strong>{{ $assignment->requiredSkill->skill_name ?? 'N/A'}}</strong> has been rejected by HR.</p>

        <p>Rejection Date: {{ $assignment->updated_at->format('Y-m-d H:i') }}</p>

        <p>If you have any questions regarding this decision, please contact your HR representative.</p>
    </div>

    <div class="signature">
        <p>Sincerely,</p>
        <p><strong>TDEIS HR Department</strong></p>
    </div>

    <div class="footer">
        <p>TDEIS - TRANSPARENCE DRIVEN EMPLOYEE ENSIGHT SYSTEM</p>
        <p>This is an automated notification. Please do not reply.</p>
    </div>
</body>
</html>
