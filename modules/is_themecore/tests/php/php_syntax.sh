find . -type f -name '*.php' ! -path "./vendor/*" ! -path "./tests/*" -exec $1 -l -n {} \; 2>&1 | (! grep -v "No syntax errors detected")