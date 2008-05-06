<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:fo="http://www.w3.org/1999/XSL/Format"
	version="1.0">
	<xsl:import href="file:///usr/share/xml/docbook/fo/docbook.xsl"/>

	<xsl:param name="paper.type" select="'A4'"/>
	<xsl:param name="draft.mode" select="'no'"/>
	<xsl:param name="sans.font.family" select="'DejaVuSans'"/>
	<xsl:param name="title.font.family" select="'DejaVuSans'"/>
	<xsl:param name="body.font.family" select="'DejaVuSerif'"/>
	<xsl:param name="dingbat.font.family" select="'DejaVuSerif'"/>
	<xsl:param name="monospace.font.family" select="'DejaVuSansMono'"/>

	<xsl:template match="revhistory/revision" mode="titlepage.mode">
		<xsl:variable name="revnumber" select="revnumber"/>
		<xsl:variable name="revdate"   select="date"/>
		<xsl:variable name="revauthor" select="authorinitials|author"/>
		<xsl:variable name="revremark" select="revremark|revdescription"/>
		<fo:table-row>
			<fo:table-cell number-columns-spanned="3" xsl:use-attribute-sets="revhistory.table.cell.properties">
				<fo:block>
					<xsl:apply-templates select="$revdate[1]" mode="titlepage.mode"/>
				</fo:block>
				<fo:block>
					<xsl:if test="$revnumber">
						<xsl:call-template name="gentext">
							<xsl:with-param name="key" select="'Revision'"/>
						</xsl:call-template>
						<xsl:text>: </xsl:text>
						<xsl:apply-templates select="$revnumber[1]" mode="titlepage.mode"/>
					</xsl:if>
				</fo:block>
			</fo:table-cell>
		</fo:table-row>
		<xsl:if test="$revremark">
			<fo:table-row>
				<fo:table-cell number-columns-spanned="3" xsl:use-attribute-sets="revhistory.table.cell.properties">
					<fo:block>
						<xsl:apply-templates select="$revremark[1]" mode="titlepage.mode"/>
					</fo:block>
				</fo:table-cell>
			</fo:table-row>
		</xsl:if>
	</xsl:template>

	<xsl:template match="revhistory" mode="titlepage.mode">
		<xsl:variable name="explicit.table.width">
			<xsl:call-template name="pi.dbfo_table-width"/>
		</xsl:variable>
		<xsl:variable name="table.width">
			<xsl:choose>
				<xsl:when test="$explicit.table.width != ''">
					<xsl:value-of select="$explicit.table.width"/>
				</xsl:when>
				<xsl:when test="$default.table.width = ''">
					<xsl:text>100%</xsl:text>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="$default.table.width"/>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<fo:table table-layout="fixed" width="{$table.width}" xsl:use-attribute-sets="revhistory.table.properties">
			<fo:table-column column-number="1" column-width="proportional-column-width(1)"/>
			<fo:table-column column-number="2" column-width="proportional-column-width(1)"/>
			<fo:table-column column-number="3" column-width="proportional-column-width(1)"/>
			<fo:table-body start-indent="0pt" end-indent="0pt">
				<fo:table-row>
					<fo:table-cell number-columns-spanned="3" xsl:use-attribute-sets="revhistory.table.cell.properties">
						<fo:block xsl:use-attribute-sets="revhistory.title.properties">
							<!--<xsl:call-template name="gentext">
								<xsl:with-param name="key" select="'RevHistory'"/>
							</xsl:call-template>-->
						</fo:block>
					</fo:table-cell>
				</fo:table-row>
				<xsl:apply-templates mode="titlepage.mode"/>
			</fo:table-body>
		</fo:table>
	</xsl:template>
</xsl:stylesheet>
