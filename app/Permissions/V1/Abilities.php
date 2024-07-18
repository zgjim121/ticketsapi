<?php

namespace App\Permissions\V1;

use App\Models\User;

final class Abilities
{
    public const CreateTicket = 'ticket:create';
    public const UpdateTicket = 'ticket:update';
    public const ReplaceTicket = 'ticket:replace';
    public const DeleteTicket = 'ticket:delete';

    public const UpdateOwnTicket = 'ticket:own:update';
    public const DeleteOwnTicket = 'ticket:own:delete';
    public const CreateOwnTicket = 'ticket:own:delete';
    public const CreateUSer = 'user:create';
    public const UpdateUser = 'user:update';
    public const ReplaceUser = 'user:replace';
    public const DeleteUser = 'delete:user';

    public static function getAbilities(User $user)
    {
        if ($user->is_manager) {
            return [
                self::CreateTicket,
                self::UpdateTicket,
                self::ReplaceTicket,
                self::DeleteTicket,
                self::CreateUSer,
                self::UpdateUser,
                self::ReplaceUser,
                self::DeleteUser,
            ];
        } else {
            return [
                self::CreateOwnTicket,
                self::UpdateOwnTicket,
                self::DeleteOwnTicket
            ];
        }
    }
}
