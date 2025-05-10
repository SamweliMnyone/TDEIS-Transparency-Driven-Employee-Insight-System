<!DOCTYPE html>
<html>
<head>
    <title>{{ $type === 'approval' ? 'Approval Letter' : 'Rejection Notice' }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin: 20px 0; line-height: 1.6; }
        .footer { margin-top: 50px; font-size: 12px; text-align: center; }
        .signature { margin-top: 50px; }
        .company-name { font-size: 24px; font-weight: bold; }
        .document-title { font-size: 18px; margin: 10px 0; }
        .date { text-align: right; margin-bottom: 20px; }
        .highlight { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">TDEIS</div>
        <div class="document-title">
            @if($type === 'approval')
                PROJECT ASSIGNMENT APPROVAL LETTER
            @else
                PROJECT ASSIGNMENT REJECTION NOTICE
            @endif
        </div>
    </div>

    <div class="date">
        Date: {{ now()->format('F j, Y') }}
    </div>

    <div class="content">
        <p>Dear {{ $assignment->user->name }},</p>

        @if($type === 'approval')
            <p>We are pleased to inform you that your assignment to the project <strong>{{ $assignment->project->name }}</strong>
            for the skill <strong>{{ $assignment->requiredSkill->skill_name ?? 'N/A'}}</strong> has been approved.</p>

            <div class="highlight">
                <p><strong>Assignment Details:</strong></p>
                <ul>
                    <li>Project: {{ $assignment->project->name }}</li>
                    <li>Required Skill: {{ $assignment->requiredSkill->skill_name ?? 'N/A'}}</li>
                    <li>Approval Date: {{ $assignment->updated_at->format('F j, Y') }}</li>
                </ul>
            </div>

            <p>Please contact your project manager for further instructions regarding your role in this project.</p>
        @else
            <p>We regret to inform you that your assignment to the project <strong>{{ $assignment->project->name }}</strong>
            for the skill <strong>{{ $assignment->required_skill }}</strong> has been rejected.</p>

            <div class="highlight">
                <p><strong>Assignment Details:</strong></p>
                <ul>
                    <li>Project: {{ $assignment->project->name }}</li>
                    <li>Required Skill: {{ $assignment->required_skill }}</li>
                    <li>Rejection Date: {{ $assignment->updated_at->format('F j, Y') }}</li>
                </ul>
            </div>

            <p>If you have any questions regarding this decision, please contact your HR representative.</p>
        @endif
    </div>

    <div class="signature">
        <p>Sincerely,</p>
        <p><strong>TDEIS HR Department</strong></p>
    </div>

    <div class="footer">
        <p>TDEIS - TRANSPARENCE DRIVEN EMPLOYEE ENSIGHT SYSTEM</p>
        <p>This is an official document. Please retain for your records.</p>
    </div>
</body>
</html>
