<?php

namespace App\Model\Manager;

final class StatsManager extends Manager
{
    protected ?string $table = "contacts";
    
    public function getStats(): array
    {
        $sql = $this->getQuery();
        return $this->makeBuilder()->fetch($sql, [], 'array');
    }

    private function getQuery(): string
    {
        $parts = [
            "SELECT COUNT(id) AS stats, 'contact' AS element, 'admin.contacts' AS admin_path from contacts",
            "SELECT COUNT(id) AS stats, 'membre' AS element, 'admin.users' AS admin_path from users"
        ];
        return join(" UNION ", $parts);
    }
}
