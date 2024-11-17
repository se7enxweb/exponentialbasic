echo "Some interesting numbers:"
echo "Total number of PHP code lines:"
cat `find . -name "*.php"` |  wc -l 
echo "Total number of template lines:"
cat `find . -name "*.tpl"` |  wc -l 
echo "Total number of language (.ini) lines:"
cat `find . -name "*.ini"` |  wc -l 

