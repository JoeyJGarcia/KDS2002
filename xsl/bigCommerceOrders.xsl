<?xml version='1.0'?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes" encoding="ISO-8859-1" omit-xml-declaration="yes" />

<xsl:template match="/">
    <kerussoOrders>
        <xsl:for-each select="orders/order" >
        <order>
            <xsl:call-template name="shippingInfo" />
            <xsl:call-template name="productsInfo" />
        </order>
        </xsl:for-each>
    </kerussoOrders>
</xsl:template>

<xsl:template name="productsInfo">
            <productsInfo>
                <xsl:for-each select="Product_Details/item" >
                    <xsl:variable name="sku"><xsl:value-of select="Product_SKU" /></xsl:variable>
                    <xsl:variable name="qty"><xsl:value-of select="Product_Qty" /></xsl:variable>

                    <xsl:variable name="size">
                        <xsl:call-template name="sizeTmpl" >
                            <xsl:with-param name="size_pt1"><xsl:value-of select="Product_SKU" /></xsl:with-param>
                            <xsl:with-param name="size_pt2">
                                <xsl:choose>
                                    <xsl:when test="contains(Product_Variation_Details, '(')">
                                       <xsl:value-of select="substring-before(Product_Variation_Details, ' (')" />
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <xsl:value-of select="Product_Variation_Details" />
                                    </xsl:otherwise>
                                </xsl:choose>
                            </xsl:with-param>
                        </xsl:call-template>
                    </xsl:variable>
                <product model='{$sku}' quantity='{$qty}' size='{$size}' />
                </xsl:for-each>
            </productsInfo>
</xsl:template>

<xsl:template name="sizeTmpl">
    <xsl:param name="size_pt1" />
    <xsl:param name="size_pt2" />

    <xsl:variable name="size_pt1_trimmed"><xsl:value-of select="normalize-space($size_pt1)" /></xsl:variable>
    <xsl:variable name="size_cat">
         <xsl:value-of select="substring($size_pt1_trimmed,1,3)"/>
    </xsl:variable>

    <xsl:variable name="size_size">
        <xsl:choose>
        <xsl:when test="$size_cat='SWC'">NA</xsl:when>
        <xsl:otherwise>
            <xsl:value-of select="substring-after($size_pt2,': ')" />
        </xsl:otherwise>
        </xsl:choose>
    </xsl:variable>


    <xsl:value-of select="concat($size_cat, ' - ', $size_size)" />

</xsl:template>

<xsl:template name="shippingInfo">

            <shippingInfo>
                <customerName><xsl:value-of select="Customer_Name"/></customerName>
                <addressInfo1><xsl:value-of select="Shipping_Street_1"/></addressInfo1>
                <addressInfo2><xsl:value-of select="Shipping_Street_2"/></addressInfo2>
                <city><xsl:value-of select="Shipping_Suburb"/></city>
                <state><xsl:value-of select="Shipping_State"/></state>
                <zipcode><xsl:value-of select="Shipping_Zip"/></zipcode>
                <country><xsl:value-of select="Shipping_Country"/></country>
                <xsl:call-template name="shippingMethod" >
                    <xsl:with-param name="ship-method" select="Ship_Method"/>
                </xsl:call-template>
                <xsl:variable name="oId"><xsl:value-of select="Order_ID"/></xsl:variable>
                <xsl:variable name="orderId">
                    <xsl:choose>
                        <xsl:when test="number($oId) >= number(8000)">
                            <xsl:value-of select="$oId"/>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:value-of select="$oId"/>
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:variable>
                <orderNumber><xsl:value-of select="$orderId"/></orderNumber>
            </shippingInfo>

</xsl:template>

<xsl:template name="shippingMethod">
    <xsl:param name="ship-method" />
    <xsl:variable name="sm">
        <xsl:choose>
            <xsl:when test="$ship-method = 'USPS (Priority Mail International)'" >Priority Mail International</xsl:when>
            <xsl:when test="$ship-method = 'USPS (Priority Mail - Regular)'" >PRIORITY</xsl:when>
            <xsl:when test="$ship-method = 'USPS (Priority Mail)'" >PRIORITY</xsl:when>
            <xsl:when test="$ship-method = 'UPS (3 Day Select)'" >3 Day Select</xsl:when>
            <xsl:when test="$ship-method = 'UPS (2 Day Air)'" >2 Day Air</xsl:when>
            <xsl:when test="$ship-method = 'UPS (Ground)'" >Ground</xsl:when>
            <xsl:when test="$ship-method = 'Free Shipping'" >Free Shipping</xsl:when>
        </xsl:choose>
    </xsl:variable>
    <xsl:variable name="smv">
        <xsl:choose>
            <xsl:when test="$ship-method = 'USPS (Priority Mail International)'" >26</xsl:when>
            <xsl:when test="$ship-method = 'USPS (Priority Mail - Regular)'" >8</xsl:when>
            <xsl:when test="$ship-method = 'USPS (Priority Mail)'" >8</xsl:when>
            <xsl:when test="$ship-method = 'UPS (3 Day Select)'" >6</xsl:when>
            <xsl:when test="$ship-method = 'UPS (2 Day Air)'" >9</xsl:when>
            <xsl:when test="$ship-method = 'UPS (Ground)'" >3</xsl:when>
            <xsl:when test="$ship-method = 'Free Shipping'" >8</xsl:when>
        </xsl:choose>
    </xsl:variable>

                <shippingMethod><xsl:value-of select="$sm"/></shippingMethod>
                <shippingMethodValue><xsl:value-of select="$smv"/></shippingMethodValue>
</xsl:template>

</xsl:stylesheet>
