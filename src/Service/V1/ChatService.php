<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1;

use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\TypeCaster;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 *
 * @psalm-type TListQuery = array{
 *      chat_id_list?: list<int>,
 *      page?: int,
 *      page_size?: int,
 * }
 * @psalm-type THistoryQuery = array{
 *      from_message_id?: int,
 *      limit?: int,
 * }
 */
class ChatService extends AbstractService
{
    /**
     * Retrieves a list of chats in which a seller participates.
     *
     * @param TListQuery $query
     *
     * @return array
     */
    public function list(array $query = [])
    {
        $query = ArrayHelper::pick($query, ['chat_id_list', 'page', 'page_size']);
        $query = TypeCaster::castArr($query, ['page' => 'int', 'page_size' => 'int']);

        return $this->request('POST', '/v1/chat/list', $query ?: '{}');
    }

    /**
     * Retreives message history in a chat.
     *
     * @param THistoryQuery $query
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
     * @see https://api-seller.ozon.ru/apiref/en/#t-title_post_sendfile
     */
    public function sendFile(string $chatId, \SplFileInfo $file)
    {
        $arr = [
            'chat_id'        => $chatId,
            'base64_content' => base64_encode(file_get_contents($file->getPathname())),
            'name'           => $file->getBasename(),
        ];
        $response = $this->request('POST', '/v1/chat/send/file', $arr);

        return 'success' === $response;
    }

    /**
     * @see https://api-seller.ozon.ru/apiref/ru/#t-title_post_chatstart
     *
     * @return string Chat ID
     */
    public function start(string $postingNumber): string
    {
        $arr = [
            'posting_number' => $postingNumber,
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
