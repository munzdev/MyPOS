@ECHO OFF
FOR /F %%T IN ('wmic process where^(commandline like "%%server.php%%"^) get ProcessId^|more +1') DO (
SET /A ProcessId=%%T) &GOTO SkipLine
:SkipLine
taskkill /F /PID %ProcessId%