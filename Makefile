all:
	asciidoc --doctype=article -b docbook -a encoding=latin2 README.txt
	xsltproc titlepage-hacks.xsl README.xml >README.fo
	fop -c /usr/share/fop/dejavu-ttf.xml -fo README.fo -pdf README.pdf

clean:
	rm -f README.{pdf,fo,xml}
