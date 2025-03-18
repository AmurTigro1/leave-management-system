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

        $employees = [
            [ 'employee' , 'LADY LENNOIRE' , '05861', 'LADY LENNOIRE', 'ABAD' , 'LGOO VI'],
            [ 'employee' , 'NORMAN' , '06793', 'NORMAN', 'ALI' , 'LGOO VI'],
            [ 'employee' , 'JENNIFER' , '05871', 'JENNIFER', 'AMIHAN' , 'LGOO VI'],
            [ 'admin' , 'LERAVE' , 'COS1', 'LERAVE', 'ANOC' , 'AA II'],
            [ 'employee' , 'NIKKO AUDREY' , 'JO1', 'NIKKO AUDREY', 'ARANAS' , 'JO'],
            [ 'employee' , 'ERIS MARK', 'COS2', 'ERIS MARK' , 'AYA-AY', 'PEO II'],
            [ 'employee' , 'JOCELYN', '05821', 'JOCELYN', 'BANDALA', 'LGOO VI'],
            [ 'employee' , 'WILFRANS', '05874', 'WILFRANS', 'BANGALAO', 'LGOO VI'],
            [ 'employee' , 'REGINA GINA', '05674', 'REGINA GINA', 'BASTES', 'LGOO VII'],
            [ 'employee' , 'KAREN ANN', '05951', 'KAREN ANN', 'BENIGA', 'LGOO VI'],
            [ 'employee' , 'EUNICE ANNE', '05912', 'EUNICE ANNE', 'BONIEL', 'LGOO VI'],
            [ 'employee' , 'NICANOR', '05802', 'NICANOR', 'BUNGABONG', 'LGOO VI'],
            [ 'employee' , 'MICHAEL', '05853', 'MICHAEL', 'CABANAG', 'LGOO VI'],
            [ 'employee' , 'CHRISTINE ROSE', '05877', 'CHRISTINE ROSE', 'CAGAMPANG', 'LGOO VI'],
            [ 'employee' , 'REDEMCION', '05389', 'REDEMCION', 'CAG-ONG', 'LGOO VI'],
            [ 'employee' , 'JEANETTE', '05786', 'JEANETTE', 'CAMILOTES', 'LGOO III'],
            [ 'employee' , 'GLENDA', 'COS3', 'GLENDA', 'CAMPECINO', 'ENGINEER II'],
            [ 'employee' , 'CARLOS FALCON', 'COS4', 'CARLOS FALCON', 'CELOSIA', 'ENGINEER II'],
            [ 'employee' , 'ERIKA NICOLE', '001', 'ERIKA NICOLE', 'CORONA', 'LGOO II'],
            [ 'employee' , 'GERALYN JANETTE', 'COS5', 'GERALYN JANETTE', 'CORONA', 'PEO I'],
            [ 'employee' , 'DYOSA MARIE', '05856', 'DYOSA MARIE', 'COSARE', 'LGOO VI'],
            [ 'employee' , 'ROSALINDA', '002', 'ROSALINDA', 'DAHUNOG', 'LGOO II'],
            [ 'employee' , 'ANTHONY DEI', '05857', 'ANTHONY DEI', 'DALIDA', 'LGOO VI'],
            [ 'employee' , 'ADONIS', '05828', 'ADONIS', 'DAMALERIO', 'LGOO VI'],
            [ 'employee' , 'BENIGNA', 'JO2', 'BENIGNA', 'DAMASIN', 'JO'],
            [ 'employee' , 'JUDY GRACE', '05858', 'JUDY GRACE', 'DOMINGUEZ', 'LGOO VI'],
            [ 'employee' , 'CLYDE', '05876', 'CLYDE', 'EBOJO', 'LGOO VI'],
            [ 'employee' , 'RHEA JOY', '05888', 'RHEA JOY', 'FIGUEROA', 'LGOO VI'],
            [ 'hr' , 'MYLOVE', '05855', 'MYLOVE', 'FLOOD', 'LGOO VI'],
            [ 'employee' , 'HYACINTH', '05879', 'HYACINTH', 'GARROTE', 'LGOO VI'],
            [ 'employee' , 'MEAH HECELL', '06030', 'MEAH HECELL', 'GENOVIA', 'LGOO II'],
            [ 'employee' , 'RUEL', 'COS6', 'RUEL', 'GO', 'ENGINEER II'],
            [ 'supervisor' , 'JEROME', '05675', 'JEROME', 'GONZALES', 'LGOO VIII'],
            [ 'employee' , 'FLORENCIO JR.', '05805', 'FLORENCIO JR.', 'HALASAN', 'LGOO VI'],
            [ 'employee' , 'MA. SHARON', '05130', 'MA. SHARON', 'HALASAN', 'LGOO VI'],
            [ 'employee' , 'MONA LISSA', '05863', 'MONA LISSA', 'HINOG', 'LGOO VI'],
            [ 'employee' , 'ISMAEL VINCENT', '05783', 'ISMAEL VINCENT', 'IGCALINOS', 'LGOO VI'],
            [ 'employee' , 'JED', '05993', 'JED', 'IGHOT', 'LGOO III'],
            [ 'employee' , 'DRIB LAURENCE', '05881', 'DRIB LAURENCE', 'INGLES', 'LGOO VI'],
            [ 'employee' , 'DIOLITO', '05968', 'DIOLITO', 'IYOG', 'LGOO V'],
            [ 'employee' , 'MAURA', '05789', 'MAURA', 'JUSTOL', 'LGOO VI'],
            [ 'employee' , 'LEE JOSHUA', '05997', 'LEE JOSHUA', 'KAINDOY', 'ADA IV'],
            [ 'employee' , 'ULDARICK', '05691', 'ULDARICK', 'LADORES', 'ADA VI'],
            [ 'employee' , 'GLENDA', '05840', 'GLENDA', 'LAUDE', 'LGOO VI'],
            [ 'employee' , 'MARIA LUZ', '05873', 'MARIA LUZ', 'LINTUA', 'LGOO VI'],
            [ 'employee' , 'ANGELO', '05781', 'ANGELO', 'MAHINAY', 'LGOO VI'],
            [ 'employee' , 'ELVIRA', '05836', 'ELVIRA', 'MANDIN', 'LGOO VI'],
            [ 'employee' , 'RUBEN', '05972', 'RUBEN', 'MANLANGIT', 'ADA IV'],
            [ 'employee' , 'TED', '05731', 'TED', 'MASCARINAS', 'LGOO VI'],
            [ 'admin' , 'KHANDA', 'COS7', 'KHANDA', 'MEDEQUISO', 'AA II'],
            [ 'employee' , 'RICARDO JR.', 'COS8', 'RICARDO JR.', 'MONTANEZ', 'ENGINEER II'],
            [ 'employee' , 'NINA CHRISTINE', '05882', 'NINA CHRISTINE', 'MONTEJO', 'LGOO VI'],
            [ 'employee' , 'JOSIE', '05774', 'JOSIE', 'MONTES', 'LGOO VI'],
            [ 'employee' , 'FIDEL', '05792', 'FIDEL', 'NARISMA', 'LGOO VI'],
            [ 'employee' , 'CECILIO', '05804', 'CECILIO', 'NISNISAN', 'LGOO VI'],
            [ 'employee' , 'JULIE MAE', '05936', 'JULIE MAE', 'NOMBRE', 'LGOO V'],
            [ 'employee' , 'JULIET', '05699', 'JULIET', 'OLALO', 'LGOO VI'],
            [ 'employee' , 'FAYE ARIELLE', '003', 'FAYE ARIELLE', 'OLIQUINO', 'LGOO II'],
            [ 'employee' , 'JUN ARCY', '05995', 'JUN ARCY', 'PACLEB', 'LGOO III'],
            [ 'employee' , 'MA. REINA', '05697', 'MA. REINA', 'QUILAS', 'LGOO VII'],
            [ 'employee' , 'JOSE RUBEN', '05847', 'JOSE RUBEN', 'RACHO', 'LGOO VI'],
            [ 'employee' , 'EMMYLOU', '05878', 'EMMYLOU', 'RAMA', 'LGOO VI'],
            [ 'employee' , 'ANA THERESA', '05880', 'ANA THERESA', 'RASONABE', 'LGOO VI'],
            [ 'employee' , 'MA. LEIZL', '05793', 'MA. LEIZL', 'REDITA', 'AAS II'],
            [ 'employee' , 'SARAH KRISTINA', '05957', 'SARAH KRISTINA', 'ROMANILLOS', 'LGOO VI'],
            [ 'employee' , 'JOGEPONS', '05803', 'JOGEPONS', 'RULOMA', 'LGOO VI'],
            [ 'employee' , 'JUN IAN', '05862', 'JUN IAN', 'SURIC', 'LGOO VI'],
            [ 'employee' , 'JOSE JEKERI', '05713', 'JOSE JEKERI', 'TANINGCO', 'LGOO VI'],
            [ 'employee' , 'JOYCELOU', '05994', 'JOYCELOU', 'TELMO', 'LGOO III'],
            [ 'employee' , 'LORENZO', '004', 'LORENZO', 'TORERO', 'LGOO II'],
            [ 'employee' , 'RACHEL', '05899', 'RACHEL', 'TORREMOCHA', 'LGOO VI'],
            [ 'employee' , 'JESSA JAN', '005', 'JESSA JAN', 'TRAZO', 'LGOO II'],
            [ 'employee' , 'JAYSON', '05869', 'JAYSON', 'TUMALE', 'LGOO VI'],
            [ 'employee' , 'RHOEL', '05706', 'RHOEL', 'TUMARAO', 'LGOO VI'],
            [ 'employee' , 'NILDA', '05839', 'NILDA', 'UNAJAN', 'LGOO VI'],
            [ 'employee' , 'MARY ANN', '05819', 'MARY ANN', 'VERGA', 'LGOO VI'],
            [ 'employee' , 'LINDSEY MARIE', '05941', 'LINDSEY MARIE', 'VISMANOS', 'LGOO VI'],
        ];

        foreach ($employees as $employee) {
            User::factory()->create([
                'name' => $employee[1],
                'employee_code' => $employee[2],
                'first_name' => $employee[3],
                'last_name' => $employee[4],
                'department' => 'Provincial Office',
                'birthday' => '2002-03-15',
                'email' => strtolower(str_replace(' ', '', $employee[2])) . '@dilgbohol.com',
                'password' => bcrypt('password'),
                'role' => $employee[0],
                'position' => $employee[5],
            ]);
        }
        
    }
}
