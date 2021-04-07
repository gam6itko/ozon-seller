<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1;

use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class ChatService extends AbstractService
{
    /**
     * Retrieves a list of chats in which a seller participates.
     *
     * @param array $query ['chat_id_list', 'page', 'page_size']
     *
     * @return array
     */
    public function list(array $query = [])
    {
        $query = ArrayHelper::pick($query, ['chat_id_list', 'page', 'page_size']);

        return $this->request('POST', '/v1/chat/list', $query ? $query : '{}');
    }

    /**
     * Retreives message history in a chat.
     *
     * @return array
     */
    public function history(string $chatId, array $query = [])
    {
        $query = ArrayHelper::pick($query, ['from_message_id', 'limit']);

        $query['chat_id'] = $chatId;

        return $this->request('POST', '/v1/chat/history', $query);
    }

    /**
     * Sends a message in an existing chat with a customer.
     */
    public function sendMessage(string $chatId, string $text): bool
    {
        $arr = [
            'chat_id' => $chatId,
            'text'    => $text,
        ];

        $response = $this->request('POST', '/v1/chat/send/message', $arr);

        return 'success' === $response;
    }

    /**
     * Sends a file in an existing chat with a customer.
     *
     * @param string $base64Content File encoded in base64 string
     * @param string $chatId        Unique chat ID
     * @param string $name          File name with extension
     *
     * @return array|string
     */
    public function sendFile(string $base64Content, string $chatId, string $name)
    {
        $arr = [
            'chat_id'        => $chatId,
            'base64_content' => $base64Content,
            'name'           => $name,
        ];
        $response = $this->request('POST', '/v1/chat/send/file', $arr);

        return 'success' === $response;
    }

    /**
     * Creates a new chat with a customer related to a specific order.
     * For example, if a seller has some questions regarding delivery address, he can simply contact a buyer via new chat.
     *
     * @return string Chat ID
     */
    public function start(int $orderId): string
    {
        $arr = [
            'order_id' => $orderId,
        ];

        return $this->request('POST', '/v1/chat/start', $arr)['chat_id'];
    }

    public function updates(string $chatId, string $fromMessageId, int $limit = 100)
    {
        $arr = [
            'chat_id'         => $chatId,
            'from_message_id' => $fromMessageId,
            'limit'           => $limit,
        ];

        return $this->request('POST', '/v1/chat/updates', $arr);
    }
}
