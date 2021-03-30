powershell -command "iwr -outf xampp.exe https://www.apachefriends.org/xampp-files/7.4.16/xampp-windows-x64-7.4.16-0-VC15-installer.exe"
xampp.exe --mode unattended --launchapps 0
powershell -command "iwr -outf node.msi https://nodejs.org/dist/v14.16.0/node-v14.16.0-x64.msi"
msiexec.exe /a node.msi /qb
setx PATH=%APPDATA%\npm;C:\nodejs\;%PATH%
powershell -command "npm install --global yarn"
Xcopy data C:\xampp\htdocs /E /H /C /I
cd C:\xampp\htdocs\viewer
yarn install