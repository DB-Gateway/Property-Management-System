$ErrorActionPreference = 'Stop'
$pmsWorkspace = (Resolve-Path -LiteralPath (Join-Path $PSScriptRoot '..')).Path
$pmsArtisan = Join-Path $pmsWorkspace 'artisan'
$pmsPhp = (Get-Command php -ErrorAction Stop).Source
$pmsLogDirectory = Join-Path $pmsWorkspace 'storage\logs'
New-Item -ItemType Directory -Path $pmsLogDirectory -Force | Out-Null

foreach ($pmsWorker in @(
    @{ Name = 'notification-queue'; Command = 'queue:work database --queue=default --sleep=3 --tries=3 --timeout=30' },
    @{ Name = 'notification-scheduler'; Command = 'schedule:work' }
)) {
    $pmsExisting = Get-CimInstance Win32_Process -Filter "Name = 'php.exe'" | Where-Object {
        $_.CommandLine -and $_.CommandLine.Contains($pmsArtisan) -and $_.CommandLine.Contains($pmsWorker.Command.Split(' ')[0])
    }
    if ($pmsExisting) {
        Write-Output "$($pmsWorker.Name) already running (PID $($pmsExisting.ProcessId -join ', '))."
        continue
    }
    $pmsProcess = Start-Process -FilePath $pmsPhp -ArgumentList "`"$pmsArtisan`" $($pmsWorker.Command)" -WorkingDirectory $pmsWorkspace -WindowStyle Hidden -PassThru `
        -RedirectStandardOutput (Join-Path $pmsLogDirectory ($pmsWorker.Name + '.log')) `
        -RedirectStandardError (Join-Path $pmsLogDirectory ($pmsWorker.Name + '-error.log'))
    Write-Output "$($pmsWorker.Name) started (PID $($pmsProcess.Id))."
}
