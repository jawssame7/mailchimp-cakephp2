<?php

App::uses('ApiClient', 'MailchimpTransactional');

/**
 * Mailchimp コンポーネント
 */
class MailChimpComponent  extends Component
{

    public ?\MailchimpTransactional\ApiClient $_mailchimp = null;

    /**
     * 初期処理
     * @param Controller $controller
     */
    function initialize(Controller $controller) {
        try {
            $this->_mailchimp = new MailchimpTransactional\ApiClient();

            // https://stackoverflow.com/questions/65232548/why-got-error-invalid-key-from-the-metadata-api-of-mailchimp/65234912
            $this->_mailchimp->setApiKey('GY0R8PKCBMSrbxoJISjikg');

            $response = $this->_mailchimp->users->ping();
            CakeLog::debug($response);
            $this->sendMail($this->_message);


        } catch (Error $e) {
            echo 'Error: ',  $e->getMessage(), "\n";
            CakeLog::error($e->getMessage());
        }
        //print_r($response);
    }

    function sendMail($message)
    {
//        $txt = '日本語をいれてみる';
//        $message['text'] = $txt;
//        $tpl = $this->getMandrillTemplate();
//        $content = $tpl->text;
//
//        $message['from_email'] = $tpl->from_email;
//        $message['subject'] = $tpl->subject;
//        $message['text'] = $content;

        // cakeのメールテンプレートを使って
        $content = $this->cakeMailTemplateCompile();
        $message['text'] = $content['text'];

        try {

            $response = $this->_mailchimp->messages->send(["message" => $message]);
            var_dump($response);
            CakeLog::debug('メール送信');
        } catch (Error $e) {
            echo 'Error: ', $e->getMessage(), "\n";
            CakeLog::error($e->getMessage());
        }
    }

    private $_message = [
        "from_email" => "hogehoge@chan-same.me",
        "subject" => "タイトルサブジェクト",
        "text" => "Welcome to Mailchimp Transactional!",
        "to" => [
            [
                "email" => "test@chan-same.me",
                "type" => "to"
            ],
            [
                "email" => "test2@chan-same.me",
                "type" => "to"
            ],
            [
                "email" => "test3@chan-same.me",
                "type" => "to"
            ],
            [
                "email" => "test4@chan-same.me",
                "type" => "to"
            ]
        ]
    ];

    /**
     * Mandrillに設定してあるTemplateを取得して返す
     * @return Exception|\GuzzleHttp\Exception\RequestException|mixed|\Psr\Http\Message\StreamInterface
     */
    function getMandrillTemplate()
    {
        $response = $this->_mailchimp->templates->info(["name" => "テンプレート日本語名"]);
        //print_r('メールテンプレート');
        var_dump($response);
        CakeLog::debug('メールテンプレート');
        return $response;
    }

    function cakeMailTemplateCompile()
    {
        App::import ( 'Vendor', 'AppEmail');
        // メールテンプレートの
        $appEmail = new AppEmail();


        $content = $appEmail->compile('test', ['name' => 'テスト名前', 'hoge' => 'メッセージ']);

//        var_dump($content);
        return $content;
    }

}