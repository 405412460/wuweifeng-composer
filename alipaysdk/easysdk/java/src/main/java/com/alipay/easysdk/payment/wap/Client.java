// This file is auto-generated, don't edit it. Thanks.
package com.alipay.easysdk.payment.wap;

import com.aliyun.tea.*;
import com.alipay.easysdk.payment.wap.models.*;
import com.alipay.easysdk.kernel.BaseClient;

public class Client extends BaseClient {
    public Client(Config config) throws Exception {
        super(TeaModel.buildMap(config));
    }


    public AlipayTradeWapPayResponse pay(String subject, String outTradeNo, String totalAmount, String quitUrl, String returnUrl) throws Exception {
        java.util.Map<String, String> systemParams = TeaConverter.buildMap(
            new TeaPair("method", "alipay.trade.wap.pay"),
            new TeaPair("app_id", _getConfig("appId")),
            new TeaPair("timestamp", _getTimestamp()),
            new TeaPair("format", "json"),
            new TeaPair("version", "1.0"),
            new TeaPair("alipay_sdk", _getSdkVersion()),
            new TeaPair("charset", "UTF-8"),
            new TeaPair("sign_type", _getConfig("signType")),
            new TeaPair("app_cert_sn", _getMerchantCertSN()),
            new TeaPair("alipay_root_cert_sn", _getAlipayRootCertSN())
        );
        java.util.Map<String, Object> bizParams = TeaConverter.buildMap(
            new TeaPair("subject", subject),
            new TeaPair("out_trade_no", outTradeNo),
            new TeaPair("total_amount", totalAmount),
            new TeaPair("quit_url", quitUrl),
            new TeaPair("product_code", "QUICK_WAP_WAY")
        );
        java.util.Map<String, String> textParams = TeaConverter.buildMap(
            new TeaPair("return_url", returnUrl)
        );
        String sign = _sign(systemParams, bizParams, textParams, _getConfig("merchantPrivateKey"));
        java.util.Map<String, String> response = TeaConverter.buildMap(
            new TeaPair("body", _generatePage("POST", systemParams, bizParams, textParams, sign))
        );
        return TeaModel.toModel(response, new AlipayTradeWapPayResponse());
    }
}
