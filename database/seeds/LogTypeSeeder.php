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
            'description' => 'API kérés'
        ]);

        LogType::create([
            'name' => 'add.hint',
            'description' => 'Új Hint'
        ]);

        LogType::create([
            'name' => 'auth.sch.callback',
            'description' => 'AuthSCH Bejelentkezés'
        ]);

        LogType::create([
            'name' => 'auth.sch.redirect',
            'description' => 'AuthSCH Átirányítás'
        ]);

        LogType::create([
            'name' => 'delete.api.tokens',
            'description' => 'Régi API kulcsok törlése'
        ]);

        LogType::create([
            'name' => 'delete.hint',
            'description' => 'Hint törlése'
        ]);

        LogType::create([
            'name' => 'delete.moderator',
            'description' => 'Moderátor törlése'
        ]);

        LogType::create([
            'name' => 'delete.static_message',
            'description' => 'Statikus üzenet törlése'
        ]);

        LogType::create([
            'name' => 'edit.static_message',
            'description' => 'Statikus üzenet szerkesztése'
        ]);

        LogType::create([
            'name' => 'generate.api.tokens',
            'description' => 'API tokenek törlése'
        ]);

        LogType::create([
            'name' => 'guess.riddle',
            'description' => 'Riddle próbálkozás'
        ]);


        LogType::create([
            'name' => 'guess.riddle.fail',
            'description' => 'Riddle próbálkozás (sikertelen)'
        ]);

        LogType::create([
            'name' => 'hint.ask',
            'description' => 'Hint kérése'
        ]);

        LogType::create([
            'name' => 'lockdown',
            'description' => 'Weboldal lezárása'
        ]);

        LogType::create([
            'name' => 'new.moderator',
            'description' => 'Új moderátor'
        ]);


        LogType::create([
            'name' => 'new.riddle',
            'description' => 'Új riddle'
        ]);

        LogType::create([
            'name' => 'new.static_message',
            'description' => 'Új statikus üzenet'
        ]);

        LogType::create([
            'name' => 'page.view',
            'description' => 'Oldal megtekintése'
        ]);

        LogType::create([
            'name' => 'profile.edit',
            'description' => 'Profil szerkesztése'
        ]);

        LogType::create([
            'name' => 'reset_riddles',
            'description' => 'Riddle reset'
        ]);

        LogType::create([
            'name' => 'riddle.approve',
            'description' => 'Riddle elfogadása'
        ]);

        LogType::create([
            'name' => 'riddle.block',
            'description' => 'Riddle tiltása'
        ]);

        LogType::create([
            'name' => 'riddle.edit',
            'description' => 'Riddle szerkesztése'
        ]);

        LogType::create([
            'name' => 'riddle.help',
            'description' => 'Segítség kérése'
        ]);

        LogType::create([
            'name' => 'riddle.help.attempt',
            'description' => 'Segítség kérése (sikertelen)'
        ]);

        LogType::create([
            'name' => 'riddle.solve',
            'description' => 'Riddle megoldása'
        ]);

        LogType::create([
            'name' => 'send.help',
            'description' => 'Segítség küldése'
        ]);

        LogType::create([
            'name' => 'sequence.add.riddle',
            'description' => 'Riddle sorrendhez adása'
        ]);

        LogType::create([
            'name' => 'sequence.move.riddle.down',
            'description' => 'Riddle lefelé mozgatása'
        ]);

        LogType::create([
            'name' => 'sequence.move.riddle.up',
            'description' => 'Riddle felfelé mozgatása'
        ]);

        LogType::create([
            'name' => 'user.block',
            'description' => 'Felhasználó tiltása'
        ]);

        LogType::create([
            'name' => 'user.modify',
            'description' => 'Felhasználó módosítása'
        ]);

        LogType::create([
            'name' => 'user.unblock',
            'description' => 'Felhasználó feloldása'
        ]);
    }
}
