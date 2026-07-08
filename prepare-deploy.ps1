# prepare-deploy.ps1
Write-Host "==============================================" -ForegroundColor Cyan
Write-Host "   PREPARING DEPLOYMENT FOR FREEPDFDOCEDITOR" -ForegroundColor Cyan
Write-Host "==============================================" -ForegroundColor Cyan

# Step 1: Compile Vite Assets
Write-Host "`nStep 1: Compiling assets with Vite (npm run build)..." -ForegroundColor Yellow
npm run build

if ($LASTEXITCODE -ne 0) {
    Write-Host "Error compiling assets. Make sure 'npm install' was run and Vite compiles successfully." -ForegroundColor Red
    Exit 1
}

# Step 2: Prepare temporary build folders
Write-Host "`nStep 2: Preparing zip folders..." -ForegroundColor Yellow
$workspace = Get-Location
$deployDir = Join-Path $workspace "deploy_temp"
$coreTemp = Join-Path $deployDir "core"
$publicTemp = Join-Path $deployDir "public_html"

# Remove old deploy temp folder if exists
if (Test-Path $deployDir) {
    Remove-Item -Path $deployDir -Force -Recurse
}

# Create temp directories
New-Item -ItemType Directory -Path $coreTemp | Out-Null
New-Item -ItemType Directory -Path $publicTemp | Out-Null

# Copy public contents to public_html temp
Copy-Item -Path "$workspace\public\*" -Destination $publicTemp -Recurse -Force

# Rewrite paths in index.php for remote subfolder structure
$indexPath = Join-Path $publicTemp "index.php"
if (Test-Path $indexPath) {
    Write-Host "Rewriting index.php paths for remote deployment..." -ForegroundColor Yellow
    $indexContent = Get-Content -Path $indexPath -Raw
    $indexContent = $indexContent -replace "\.\./storage/", "../core/storage/"
    $indexContent = $indexContent -replace "\.\./vendor/", "../core/vendor/"
    $indexContent = $indexContent -replace "\.\./bootstrap/", "../core/bootstrap/"
    Set-Content -Path $indexPath -Value $indexContent -Force
}

# Copy core files to core temp (excluding folders)
$excludeList = @(".git", "node_modules", "public", ".env", "tests", "deploy_temp", "core.zip", "public.zip", "prepare-deploy.ps1")
Get-ChildItem -Path $workspace | Where-Object { $_.Name -notin $excludeList } | ForEach-Object {
    Copy-Item -Path $_.FullName -Destination $coreTemp -Recurse -Force
}

# Step 3: Zip the outputs
Write-Host "`nStep 3: Creating ZIP archives..." -ForegroundColor Yellow
$coreZip = Join-Path $workspace "core.zip"
$publicZip = Join-Path $workspace "public.zip"

tar.exe -a -c -f "$coreZip" -C "$coreTemp" .
tar.exe -a -c -f "$publicZip" -C "$publicTemp" .

# Clean up temp directories
Remove-Item -Path $deployDir -Recurse -Force

Write-Host "`n==============================================" -ForegroundColor Green
Write-Host "   SUCCESS: DEPLOYMENT ARCHIVES GENERATED!" -ForegroundColor Green
Write-Host "==============================================" -ForegroundColor Green
Write-Host "Generated files in workspace:" -ForegroundColor Gray
Write-Host "1. core.zip (Upload to: /home/username/core/)" -ForegroundColor White
Write-Host "2. public.zip (Upload to: /home/username/public_html/)" -ForegroundColor White
Write-Host "==============================================" -ForegroundColor Green
