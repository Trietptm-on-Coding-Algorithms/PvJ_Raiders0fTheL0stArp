Windows Toolkit
=======================

Do not forget to download and install EMET!
---------

* [`Sysinternals.zip`](Sysinternals.zip)

    Added by John Hammond. `Autoruns` - Check what is set to run on startup, `Process Explorer` - Check the processes in a process tree (and SIGNATURE), `PsLoggedOn` - Check who is logged on,  `TCPView` -  Check active connections and listeners, `Strings` - Check the strings of a binary (like we do in Linux), `PsExec` - a poor man's remote control

* [`enable_powershell_transcription_logging.bat`](enable_powershell_transcription_logging.bat)

    Added by John Hammond. Batch script that turns on PowerShell transcription logging in `C:\PS_transcription\`.

* [`enable_powershell_constrained_language.ps1`](enable_powershell_constrained_language.ps1)

    Added by John Hammond. PowerShell script to set an environment variable and turn on PowerShell Constrained Language Mode. This does not to be a full script (it is only one line) but I wanted it visible so we are sure to use it! 

* [`disable_powershell_v2.ps1`](disable_powershell_v2.ps1)

    Added by John Hammond. PowerShell switch to disable PowerShell V2 (can be used to avoid ExecutionPolicy or Constrained Language Mode). This does not to be a full script (it is only one line) but I wanted it visible so we are sure to use it! 

* [`forensics.bat`](forensics.bat)

    Added by G1d30N. Batch script that runs system information enumeration and store it in `C:\Users\`.

* [`WindowsEnum.ps1`](WindowsEnum.ps1)

    Added by John Hammond. Credited repo is here: [https://github.com/absolomb/WindowsEnum](https://github.com/absolomb/WindowsEnum). Running the extended check may take a long time: `powershell -nologo -executionpolicy bypass -file WindowsEnum.ps1 extended`

* [`DeepBlue.ps1`](DeepBlue.ps1)

    Added by John Hammond. Credited repo is here: [https://github.com/sans-blue-team/DeepBlueCLI](https://github.com/sans-blue-team/DeepBlueCLI). Awesome utility for quickly scanning logs and finding trouble. Usage like: `.\DeepBlue.ps1 -log security` or `.\DeepBlue.ps1 -log system`

* [`PoSH_R2.ps1`](PoSH_R2.ps1)

    Added by John Hammond. Credited repo is here: [https://github.com/WiredPulse/PoSh-R2](https://github.com/WiredPulse/PoSh-R2).  This tool is awesome, given a list of Windows IP addresses, it will use WMI to query Autorun entries , Disk info , Environment variables , Event logs (50 lastest) , Installed Software , Logon sessions , List of drivers , List of mapped network drives , List of running processes , Logged in user , Local groups , Local user accounts , Network configuration , Network connections , Patches , Scheduled tasks with AT command , Shares , Services , System Information. For easy viewing of results, you can run `(import-csv .<some_file.csv> | out-gridview`.

* [`windows_xp_stig.bat`](windows_xp_stig.bat)

    Added by John Hammond. A force-compliance script for Windows XP Security Technical Implementation Guide. Just makes a ton of registry tweaks to harden the box.

* [`windows_7_stig.bat`](windows_7_stig.bat)

    Added by John Hammond. A force-compliance script for Windows 7 Security Technical Implementation Guide. Just makes a ton of registry tweaks to harden the box.

* [`WN10_Stigs.ps1`](WN10_Stigs.ps1)

    Added by John Hammond. A force-compliance script for Windows 10 Security Technical Implementation Guide. Just makes a ton of registry tweaks to harden the box.

* [`WS16_Stigs.ps1`](WS16_Stigs.ps1)

    Added by John Hammond. A force-compliance script for Windows Server 2016 Security Technical Implementation Guide. Just makes a ton of registry tweaks to harden the box.