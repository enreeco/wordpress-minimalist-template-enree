#!/usr/bin/env node

const fs = require('fs');
const path = require('path');
const archiver = require('archiver');

// Configuration
const THEME_NAME = 'enree-minimal';
const BUILD_DIR = path.join(__dirname, '.release');
const THEME_SOURCE = path.join(__dirname, '..');
const EXCLUDE_PATTERNS = [
  '.git',
  '.vscode',
  '.DS_Store',
  'Thumbs.db',
  'desktop.ini',
  '.gitignore',
  'README.md',
  'node_modules',
  'build_script',
  '.release'
];

// Colors for console output
const colors = {
  reset: '\x1b[0m',
  bright: '\x1b[1m',
  red: '\x1b[31m',
  green: '\x1b[32m',
  yellow: '\x1b[33m',
  blue: '\x1b[34m',
  magenta: '\x1b[35m',
  cyan: '\x1b[36m'
};

function log(message, color = 'reset') {
  console.log(`${colors[color]}${message}${colors.reset}`);
}

function shouldExclude(filePath) {
  const relativePath = path.relative(THEME_SOURCE, filePath);
  const fileName = path.basename(filePath);
  
  return EXCLUDE_PATTERNS.some(pattern => {
    if (pattern.includes('.')) {
      // File pattern
      return fileName === pattern || relativePath.includes(pattern);
    } else {
      // Directory pattern
      return relativePath.startsWith(pattern + path.sep) || relativePath === pattern;
    }
  });
}

function getAllFiles(dirPath, arrayOfFiles = []) {
  const files = fs.readdirSync(dirPath);
  
  files.forEach(file => {
    const fullPath = path.join(dirPath, file);
    
    if (shouldExclude(fullPath)) {
      return;
    }
    
    if (fs.statSync(fullPath).isDirectory()) {
      arrayOfFiles = getAllFiles(fullPath, arrayOfFiles);
    } else {
      arrayOfFiles.push(fullPath);
    }
  });
  
  return arrayOfFiles;
}

function cleanBuildDirectory() {
  if (fs.existsSync(BUILD_DIR)) {
    fs.rmSync(BUILD_DIR, { recursive: true, force: true });
    log('✓ Cleaned build directory', 'green');
  }
}

function createBuildDirectory() {
  if (!fs.existsSync(BUILD_DIR)) {
    fs.mkdirSync(BUILD_DIR, { recursive: true });
    log('✓ Created build directory', 'green');
  }
}

function buildTheme() {
  return new Promise((resolve, reject) => {
    const outputPath = path.join(BUILD_DIR, `${THEME_NAME}.zip`);
    const output = fs.createWriteStream(outputPath);
    const archive = archiver('zip', { zlib: { level: 9 } });
    
    output.on('close', () => {
      const sizeInMB = (archive.pointer() / 1024 / 1024).toFixed(2);
      log(`✓ Theme built successfully!`, 'green');
      log(`  File: ${path.basename(outputPath)}`, 'cyan');
      log(`  Size: ${sizeInMB} MB`, 'cyan');
      log(`  Location: ${outputPath}`, 'cyan');
      resolve();
    });
    
    archive.on('error', (err) => {
      log(`✗ Build failed: ${err.message}`, 'red');
      reject(err);
    });
    
    archive.pipe(output);
    
    // Get all files to include
    const files = getAllFiles(THEME_SOURCE);
    
    log(`📦 Building theme with ${files.length} files...`, 'blue');
    
    files.forEach(file => {
      const relativePath = path.relative(THEME_SOURCE, file);
      const archivePath = path.join(THEME_NAME, relativePath);
      
      archive.file(file, { name: archivePath });
    });
    
    archive.finalize();
  });
}

function showHelp() {
  log('\n🔧 Enree Minimal Theme Builder', 'bright');
  log('================================\n', 'bright');
  log('Usage:', 'yellow');
  log('  node build.js          Build the theme', 'cyan');
  log('  node build.js --clean  Clean build directory first', 'cyan');
  log('  node build.js --help   Show this help\n', 'cyan');
  log('Excluded files/folders:', 'yellow');
  EXCLUDE_PATTERNS.forEach(pattern => {
    log(`  - ${pattern}`, 'magenta');
  });
  log('');
}

async function main() {
  const args = process.argv.slice(2);
  
  if (args.includes('--help') || args.includes('-h')) {
    showHelp();
    return;
  }
  
  try {
    log('🚀 Starting Enree Minimal theme build...', 'bright');
    
    if (args.includes('--clean')) {
      cleanBuildDirectory();
    }
    
    createBuildDirectory();
    await buildTheme();
    
    log('\n🎉 Build completed successfully!', 'green');
    
  } catch (error) {
    log(`\n❌ Build failed: ${error.message}`, 'red');
    process.exit(1);
  }
}

// Run the build
main();
