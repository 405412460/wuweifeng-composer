<?php
/**
 *  creator: dinglei
 */

namespace Ksyun\Service;

use Ksyun\Base\V4Curl;
use \DOMDocument;

class Mem extends V4Curl
{
    protected function getConfig()
    {
        return [
            'host' => 'https://memcached.api.ksyun.com',
            'config' => [
                'timeout' => 60,  //设置timeout
                'v4_credentials' => [
                    'region' => 'cn-shanghai-2',
                    'service' => 'memcached',
                ],
            ],
        ];
    }

    protected $apiList = [

        // 清除该缓存服务下的所有数据
        'FlushCacheCluster' => [
            'url' => '/',
            'method' => 'put',
            'config' => [
                'query' => [
                    'Action' => 'FlushCacheCluster', // 调用接口名称
                    'Version' => '2018-06-27', // API版本号
                    'Engine' => 'memcached', // 缓存服务引擎
                ]
            ],
        ],
    ];

    //特殊封装  request
    public function request($api, array $config = [])
    {
        return parent::request($api, $config);
    }

    // PreloadCache 封装xml 发送
    private function proloadpost($files, $api, $config)
    {

        foreach ($files as $url) {
            $tempu = parse_url($url);
            $strdomain = $tempu['host'];
            $strPath = $tempu['path'];
            isset($domains[$strdomain]) ? $domains[$strdomain][] = $strPath : $domains[$strdomain] = [$strPath,];
        }
        $keys = array_keys($domains);

        foreach ($keys as $key) {
            $distributionId = base64_encode($key);

            $dom = new DOMDocument();
            $root = $dom->createElement("PreloadBatch");
            $dom->appendChild($root);
            $paths = $dom->createElement("Paths");
            $root->appendChild($paths);
            $items = $dom->createElement("Items");
            $paths->appendChild($items);
            foreach ($domains[$key] as $path) {
                $item = $dom->createElement("Path");
                $items->appendChild($item);
                $text = $dom->createTextNode($path);
                $item->appendChild($text);
            }
            $quantity = $dom->createElement("Quantity");
            $paths->appendChild($quantity);
            $q = $dom->createTextNode(sizeof($domains[$key]));
            $quantity->appendChild($q);
            $Caller = $dom->createElement("CallerReference");
            $paths->appendChild($Caller);
            $uuid = $dom->createTextNode($this->create_guid());
            $Caller->appendChild($uuid);
            $config['body'] = $dom->saveXML();
            $config['replace']['domain'] = $distributionId;
            //var_dump($config);
            $response = parent::request($api, $config); // 发送
            //echo $response->getStatusCode();
            //echo "\n";
            //echo (string)$response->getBody();
        }
    }

    //删除指定key 数组元素
    private function array_remove($data, $key)
    {
        if (!array_key_exists($key, $data)) {
            return $data;
        }
        $keys = array_keys($data);
        $index = array_search($key, $keys);
        if ($index !== FALSE) {
            array_splice($data, $index, 1);
        }
        return $data;
    }

    private function create_guid()
    {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);
        $uuid = '' . substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
        return $uuid;
    }

}


