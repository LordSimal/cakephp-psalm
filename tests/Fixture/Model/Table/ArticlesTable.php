<?php

namespace LordSimal\CakePHPPsalm\Tests\Fixture\Model\Table;

use Cake\ORM\Table;

/**
 * @psalm-api
 * @extends Table<array{}, \Cake\ORM\Entity>
 */
final class ArticlesTable extends Table
{
    /** @psalm-pure */
    public function articleTitle(): string
    {
        return 'Article';
    }
}
