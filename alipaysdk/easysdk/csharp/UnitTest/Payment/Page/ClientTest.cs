using NUnit.Framework;
using Alipay.EasySDK.Factory;

namespace UnitTest.Payment.Page
{
    public class ClientTest
    {
        [SetUp]
        public void SetUp()
        {
            Factory.SetOptions(TestAccount.Mini.CONFIG);
        }

        [Test]
        public void TestPay()
        {
            Alipay.EasySDK.Payment.Page.Models.AlipayTradePagePayResponse response = Factory.Payment.Page().Pay("iPhone6 16G",
                "e5b5bd79-8310-447d-b63b-0fe3a393324d", "0.10", "https://www.taobao.com");

            Assert.IsTrue(response.Body.Contains("<form name=\"punchout_form\" method=\"post\" action=\"https://openapi.alipay.com/gateway.do?"));
            Assert.IsTrue(response.Body.Contains("notify_url"));
            Assert.IsTrue(response.Body.Contains("return_url"));
            Assert.IsTrue(response.Body.Contains("<input type=\"hidden\" name=\"biz_content\" value=\"{&quot;subject&quot;:&quot;iPhone6 16G&quot;,&quot;"
                + "out_trade_no&quot;:&quot;e5b5bd79-8310-447d-b63b-0fe3a393324d&quot;,&quot;total_amount&quot;:&quot;0.10&quot;,&quot;"
                + "product_code&quot;:&quot;FAST_INSTANT_TRADE_PAY&quot;}\">"));
            Assert.IsTrue(response.Body.Contains("<input type=\"submit\" value=\"立即支付\" style=\"display:none\" >"));
            Assert.IsTrue(response.Body.Contains("<script>document.forms[0].submit();</script>"));
        }
    }
}
