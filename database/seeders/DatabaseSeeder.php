<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Lucy',
            'employee_code' => '05201',
            'first_name' => 'Lucyna',
            'middle_name' => 'David',
            'last_name' => 'Kushinada',
            'department' => 'DILG BOHOL',
            'birthday' => '2002-03-11',
            'email' => 'lucy@gmail.com',
            'password' => 'password',
            'position' => 'Ojt',
            'role' => 'employee'
        ]);

        User::factory()->create([
            'name' => 'HR',
            'employee_code' => 'COS2',
            'first_name' => 'Mylove',
            'middle_name' => 'Concha',
            'last_name' => 'Flood',
            'department' => 'DILG BOHOL',
            'birthday' => '2002-04-12',
            'email' => 'hr@gmail.com',
            'password' => 'password',
            'position' => 'Ojt',
            'role' => 'hr',
        ]);

        User::factory()->create([
            'name' => 'Supervisor',
            'employee_code' => '002',
            'first_name' => 'Jerome',
            'middle_name' => 'Gazelle',
            'last_name' => 'Gonzales',
            'department' => 'DILG BOHOL',
            'birthday' => '2002-02-15',
            'email' => 'supervisor@gmail.com',
            'password' => 'password',
            'position' => 'Ojt',
            'role' => 'supervisor',
        ]);

        User::factory()->create([
            'name' => 'Admin/Assistant',
            'employee_code' => 'JO2',
            'first_name' => 'Khanda',
            'middle_name' => 'Gazelle',
            'last_name' => 'Medequiso',
            'department' => 'DILG BOHOL',
            'birthday' => '2002-02-15',
            'email' => 'admin@gmail.com',
            'password' => 'password',
            'position' => 'Ojt',
            'role' => 'admin',
        ]);
        
        $employees = [
            [ 'LADY LENNOIRE' , '05861', 'LADY LENNOIRE', 'ABAD' , 'LGOO VI'],
            [ 'NORMAN' , '06793', 'NORMAN', 'ALI' , 'LGOO VI'],
            [ 'JENNIFER' , '05871', 'JENNIFER', 'AMIHAN' , 'LGOO VI'],
            [ 'LERAVE' , 'COS1', 'LERAVE', 'ANOC' , 'AA II'],
            [ 'NIKKO AUDREY' , 'JO1', 'NIKKO AUDREY', 'ARANAS' , 'JO'],
            [ 'ERIS MARK', 'COS2', 'ERIS MARK' , 'AYA-AY', 'PEO II'],
            [ 'JOCELYN', '05821', 'JOCELYN', 'BANDALA', 'LGOO VI'],
            [ 'WILFRANS', '05874', 'WILFRANS', 'BANGALAO', 'LGOO VI'],
            [ 'REGINA GINA', '05674', 'REGINA GINA', 'BASTES', 'LGOO VII'],
            [ 'KAREN ANN', '05951', 'KAREN ANN', 'BENIGA', 'LGOO VI'],
            [ 'EUNICE ANNE', '05912', 'EUNICE ANNE', 'BONIEL', 'LGOO VI'],
            [ 'NICANOR', '05802', 'NICANOR', 'BUNGABONG', 'LGOO VI'],
            [ 'MICHAEL', '05853', 'MICHAEL', 'CABANAG', 'LGOO VI'],
            [ 'CHRISTINE ROSE', '05877', 'CHRISTINE ROSE', 'CAGAMPANG', 'LGOO VI'],
            [ 'REDEMCION', '05389', 'REDEMCION', 'CAG-ONG', 'LGOO VI'],
            [ 'JEANETTE', '05786', 'JEANETTE', 'CAMILOTES', 'LGOO III'],
            [ 'GLENDA', 'COS3', 'GLENDA', 'CAMPECINO', 'ENGINEER II'],
            [ 'CARLOS FALCON', 'COS4', 'CARLOS FALCON', 'CELOSIA', 'ENGINEER II'],
            [ 'ERIKA NICOLE', '001', 'ERIKA NICOLE', 'CORONA', 'LGOO II'],
            [ 'GERALYN JANETTE', 'COS5', 'GERALYN JANETTE', 'CORONA', 'PEO I'],
            [ 'DYOSA MARIE', '05856', 'DYOSA MARIE', 'COSARE', 'LGOO VI'],
            [ 'ROSALINDA', '002', 'ROSALINDA', 'DAHUNOG', 'LGOO II'],
            [ 'ANTHONY DEI', '05857', 'ANTHONY DEI', 'DALIDA', 'LGOO VI'],
            [ 'ADONIS', '05828', 'ADONIS', 'DAMALERIO', 'LGOO VI'],
            [ 'BENIGNA', 'JO2', 'BENIGNA', 'DAMASIN', 'JO'],
            [ 'JUDY GRACE', '05858', 'JUDY GRACE', 'DOMINGUEZ', 'LGOO VI'],
            [ 'CLYDE', '05876', 'CLYDE', 'EBOJO', 'LGOO VI'],
            [ 'RHEA JOY', '05888', 'RHEA JOY', 'FIGUEROA', 'LGOO VI'],
            [ 'MYLOVE', '05855', 'MYLOVE', 'FLOOD', 'LGOO VI'],
            [ 'HYACINTH', '05879', 'HYACINTH', 'GARROTE', 'LGOO VI'],
            [ 'MEAH HECELL', '06030', 'MEAH HECELL', 'GENOVIA', 'LGOO II'],
            [ 'RUEL', 'COS6', 'RUEL', 'GO', 'ENGINEER II'],
            [ 'JEROME', '05675', 'JEROME', 'GONZALES', 'LGOO VIII'],
            [ 'FLORENCIO JR.', '05805', 'FLORENCIO JR.', 'HALASAN', 'LGOO VI'],
            [ 'MA. SHARON', '05130', 'MA. SHARON', 'HALASAN', 'LGOO VI'],
            [ 'MONA LISSA', '05863', 'MONA LISSA', 'HINOG', 'LGOO VI'],
            [ 'ISMAEL VINCENT', '05783', 'ISMAEL VINCENT', 'IGCALINOS', 'LGOO VI'],
            [ 'JED', '05993', 'JED', 'IGHOT', 'LGOO III'],
            [ 'DRIB LAURENCE', '05881', 'DRIB LAURENCE', 'INGLES', 'LGOO VI'],
            [ 'DIOLITO', '05968', 'DIOLITO', 'IYOG', 'LGOO V'],
            [ 'MAURA', '05789', 'MAURA', 'JUSTOL', 'LGOO VI'],
            [ 'LEE JOSHUA', '05997', 'LEE JOSHUA', 'KAINDOY', 'ADA IV'],
            [ 'ULDARICK', '05691', 'ULDARICK', 'LADORES', 'ADA VI'],
            [ 'GLENDA', '05840', 'GLENDA', 'LAUDE', 'LGOO VI'],
            [ 'MARIA LUZ', '05873', 'MARIA LUZ', 'LINTUA', 'LGOO VI'],
            [ 'ANGELO', '05781', 'ANGELO', 'MAHINAY', 'LGOO VI'],
            [ 'ELVIRA', '05836', 'ELVIRA', 'MANDIN', 'LGOO VI'],
            [ 'RUBEN', '05972', 'RUBEN', 'MANLANGIT', 'ADA IV'],
            [ 'TED', '05731', 'TED', 'MASCARINAS', 'LGOO VI'],
            [ 'KHANDA', 'COS7', 'KHANDA', 'MEDEQUISO', 'AA II'],
            [ 'RICARDO JR.', 'COS8', 'RICARDO JR.', 'MONTANEZ', 'ENGINEER II'],
            [ 'NINA CHRISTINE', '05882', 'NINA CHRISTINE', 'MONTEJO', 'LGOO VI'],
            [ 'JOSIE', '05774', 'JOSIE', 'MONTES', 'LGOO VI'],
            [ 'FIDEL', '05792', 'FIDEL', 'NARISMA', 'LGOO VI'],
            [ 'CECILIO', '05804', 'CECILIO', 'NISNISAN', 'LGOO VI'],
            [ 'JULIE MAE', '05936', 'JULIE MAE', 'NOMBRE', 'LGOO V'],
            [ 'JULIET', '05699', 'JULIET', 'OLALO', 'LGOO VI'],
            [ 'FAYE ARIELLE', '003', 'FAYE ARIELLE', 'OLIQUINO', 'LGOO II'],
            [ 'JUN ARCY', '05995', 'JUN ARCY', 'PACLEB', 'LGOO III'],
            [ 'MA. REINA', '05697', 'MA. REINA', 'QUILAS', 'LGOO VII'],
            [ 'JOSE RUBEN', '05847', 'JOSE RUBEN', 'RACHO', 'LGOO VI'],
            [ 'EMMYLOU', '05878', 'EMMYLOU', 'RAMA', 'LGOO VI'],
            [ 'ANA THERESA', '05880', 'ANA THERESA', 'RASONABE', 'LGOO VI'],
            [ 'MA. LEIZL', '05793', 'MA. LEIZL', 'REDITA', 'AAS II'],
            [ 'SARAH KRISTINA', '05957', 'SARAH KRISTINA', 'ROMANILLOS', 'LGOO VI'],
            [ 'JOGEPONS', '05803', 'JOGEPONS', 'RULOMA', 'LGOO VI'],
            [ 'JUN IAN', '05862', 'JUN IAN', 'SURIC', 'LGOO VI'],
            [ 'JOSE JEKERI', '05713', 'JOSE JEKERI', 'TANINGCO', 'LGOO VI'],
            [ 'JOYCELOU', '05994', 'JOYCELOU', 'TELMO', 'LGOO III'],
            [ 'LORENZO', '004', 'LORENZO', 'TORERO', 'LGOO II'],
            [ 'RACHEL', '05899', 'RACHEL', 'TORREMOCHA', 'LGOO VI'],
            [ 'JESSA JAN', '005', 'JESSA JAN', 'TRAZO', 'LGOO II'],
            [ 'JAYSON', '05869', 'JAYSON', 'TUMALE', 'LGOO VI'],
            [ 'RHOEL', '05706', 'RHOEL', 'TUMARAO', 'LGOO VI'],
            [ 'NILDA', '05839', 'NILDA', 'UNAJAN', 'LGOO VI'],
            [ 'MARY ANN', '05819', 'MARY ANN', 'VERGA', 'LGOO VI'],
            [ 'LINDSEY MARIE', '05941', 'LINDSEY MARIE', 'VISMANOS', 'LGOO VI'],
        ];

        foreach ($employees as $employee) {
            User::factory()->create([
                'name' => $employee[0],
                'employee_code' => $employee[1],
                'first_name' => $employee[2],
                'last_name' => $employee[3],
                'department' => 'Provincial Office',
                'birthday' => '2002-03-15',
                'email' => strtolower(str_replace(' ', '', $employee[1])) . '@dilgbohol.com',
                'password' => bcrypt('password'),
                'role' => 'employee',
                'position' => $employee[4],
            ]);
        }
        
    }
}
