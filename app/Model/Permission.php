<?php

namespace App\Model;

use Nette;

class Permission
{
    use Nette\SmartObject;

    private Nette\Database\Context $database;
    private Contractor $contractor;
    private int $currentUserId;


    public function __construct (Nette\Database\Context $database, Contractor $contractor)
    {
        $this->database = $database;
        $this->contractor = $contractor;
    }

    public function setCurrentUserId($currentUserId): void
    {
        $this->currentUserId = $currentUserId;
    }

    public function getPermissions($contractorId): array
    {
        return $this->database
            ->query('SELECT permission_id, permission_system_name FROM contractor_mn_permission NATURAL JOIN permission WHERE contractor_id = ?', $contractorId)
            ->fetchPairs('permission_id', 'permission_system_name');
    }

    public function getAllPermissions(): array
    {
        return $this->database->table('permission')->fetchPairs('permission_id', 'permission_system_name');
    }

    public function getAllPermissionsSortedByParent(): array
    {
        $result = $this->database
            ->query('SELECT * FROM permission NATURAL JOIN permission_parent ORDER BY permission_parent_order, permission_order')
            ->fetchAll();
        $parentsCount = $this->database->table('permission_parent')->count();

        $allPermissionsSortedByParent = [];
        foreach ($result as $p) {
            $parent = 1;
            while ($parent <= $parentsCount) {
                if ($p->permission_parent_order === $parent) {
                    $allPermissionsSortedByParent[$p->permission_parent_human_name][] = $p;
                }
                $parent++;
            }
        }

        return $allPermissionsSortedByParent;
    }

    public function getPermissionPresets(): array
    {
        $superadmin = array_keys($this->database->table('permission')->fetchPairs('permission_id'));
        $contractor = [1, 5, 6, 7, 8, 9, 10, 12, 13];

        return ['Superadmin' => $superadmin, 'Contractor' => $contractor];
    }

    public function insertPermissions(int $id, array $permissionsIds): void
    {
        foreach ($permissionsIds as $permissionId) {
            $this->database->table('contractor_mn_permission')->insert([
                'contractor_id' => $id,
                'permission_id' => $permissionId
            ]);
        }
    }

    public function updatePermissions(int $contractorId, array $newPermissionsIds): void
    {
        $existingPermissionsIds = array_keys($this->getPermissions($contractorId));

        // deletes all existing permissions that are not in submittedPermissionsIds
        $this->database->table('contractor_mn_permission')
            ->where('contractor_id', $contractorId)
            ->where('permission_id IN (?)', array_values(array_diff($existingPermissionsIds, $newPermissionsIds)))
            ->delete();

        // inserts newly submitted permissions that are not already in existingPermissionsIds
        $this->insertPermissions($contractorId, array_diff($newPermissionsIds, $existingPermissionsIds));

        if ($newPermissionsIds !== $this->getAllPermissions()) {
            $this->contractor->isSuperAdmin($contractorId, false);
        }
    }

    public function deletePermissions(int $contractorId): void
    {
        $this->database->table('contractor_mn_permission')
            ->where('contractor_id', $contractorId)
            ->delete();
    }
}