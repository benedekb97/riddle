<?php

use App\Models\LogType;
use Illuminate\Database\Seeder;

class LogTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LogType::create([
            'name' => 'api.request',
            'description' => 'API kérés',
            'log_category_id' => 1
        ]);

        LogType::create([
            'name' => 'add.hint',
            'description' => 'Új Hint',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'auth.sch.callback',
            'description' => 'AuthSCH Bejelentkezés',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'auth.sch.redirect',
            'description' => 'AuthSCH Átirányítás',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'delete.api.tokens',
            'description' => 'Régi API kulcsok törlése',
            'log_category_id' => 2
        ]);

        LogType::create([
            'name' => 'delete.hint',
            'description' => 'Hint törlése',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'delete.moderator',
            'description' => 'Moderátor törlése',
            'log_category_id' => 2
        ]);

        LogType::create([
            'name' => 'delete.static_message',
            'description' => 'Statikus üzenet törlése',
            'log_category_id' => 2
        ]);

        LogType::create([
            'name' => 'edit.static_message',
            'description' => 'Statikus üzenet szerkesztése',
            'log_category_id' => 2
        ]);

        LogType::create([
            'name' => 'generate.api.tokens',
            'description' => 'API tokenek törlése',
            'log_category_id' => 2
        ]);

        LogType::create([
            'name' => 'guess.riddle',
            'description' => 'Riddle próbálkozás',
            'log_category_id' => 4
        ]);


        LogType::create([
            'name' => 'guess.riddle.fail',
            'description' => 'Riddle próbálkozás (sikertelen)',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'hint.ask',
            'description' => 'Hint kérése',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'lockdown',
            'description' => 'Weboldal lezárása',
            'log_category_id' => 2
        ]);

        LogType::create([
            'name' => 'new.moderator',
            'description' => 'Új moderátor',
            'log_category_id' => 2
        ]);


        LogType::create([
            'name' => 'new.riddle',
            'description' => 'Új riddle',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'new.static_message',
            'description' => 'Új statikus üzenet',
            'log_category_id' => 2
        ]);

        LogType::create([
            'name' => 'page.view',
            'description' => 'Oldal megtekintése',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'profile.edit',
            'description' => 'Profil szerkesztése',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'reset_riddles',
            'description' => 'Riddle reset',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'riddle.approve',
            'description' => 'Riddle elfogadása',
            'log_category_id' => 3
        ]);

        LogType::create([
            'name' => 'riddle.block',
            'description' => 'Riddle tiltása',
            'log_category_id' => 3
        ]);

        LogType::create([
            'name' => 'riddle.edit',
            'description' => 'Riddle szerkesztése',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'riddle.help',
            'description' => 'Segítség kérése',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'riddle.help.attempt',
            'description' => 'Segítség kérése (sikertelen)',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'riddle.solve',
            'description' => 'Riddle megoldása',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'send.help',
            'description' => 'Segítség küldése',
            'log_category_id' => 4
        ]);

        LogType::create([
            'name' => 'sequence.add.riddle',
            'description' => 'Riddle sorrendhez adása',
            'log_category_id' => 3
        ]);

        LogType::create([
            'name' => 'sequence.move.riddle.down',
            'description' => 'Riddle lefelé mozgatása',
            'log_category_id' => 3
        ]);

        LogType::create([
            'name' => 'sequence.move.riddle.up',
            'description' => 'Riddle felfelé mozgatása',
            'log_category_id' => 3
        ]);

        LogType::create([
            'name' => 'user.block',
            'description' => 'Felhasználó tiltása',
            'log_category_id' => 2
        ]);

        LogType::create([
            'name' => 'user.modify',
            'description' => 'Felhasználó módosítása',
            'log_category_id' => 2
        ]);

        LogType::create([
            'name' => 'user.unblock',
            'description' => 'Felhasználó feloldása',
            'log_category_id' => 2
        ]);

        LogType::create([
            'name' => 'moderator.page.view',
            'description' => 'Moderátor oldal megtekintése',
            'log_category_id' => 3
        ]);

        LogType::create([
            'name' => 'admin.page.view',
            'description' => 'Admin oldal megtekintése',
            'log_category_id' => 2
        ]);
    }
}
