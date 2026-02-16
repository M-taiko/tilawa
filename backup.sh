#!/bin/bash

# Database Backup Script for Tilawa
# Run daily via cron: 0 2 * * * /path/to/tilawa/backup.sh

# Database configuration
DB_NAME="tilawa"
DB_USER="your_db_user"
DB_PASS="your_db_password"

# Backup directory
BACKUP_DIR="storage/backups"
RETENTION_DAYS=14

# Create backup directory if it doesn't exist
mkdir -p $BACKUP_DIR

# Generate backup filename with date
BACKUP_FILE="$BACKUP_DIR/tilawa_backup_$(date +%Y%m%d_%H%M%S).sql"

# Create backup
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_FILE

# Compress backup
gzip $BACKUP_FILE

# Delete backups older than retention period
find $BACKUP_DIR -name "*.sql.gz" -type f -mtime +$RETENTION_DAYS -delete

echo "Backup completed: ${BACKUP_FILE}.gz"
