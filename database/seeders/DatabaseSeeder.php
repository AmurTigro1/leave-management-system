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
        $this->call(HrSupervisorSeeder::class);
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Lucy',
            'employee_code' => '05201',
            'first_name' => 'Lucyna',
            'middle_name' => 'David',
            'last_name' => 'Kushinada',
            'gender' => 'female',
            'department' => 'DILG BOHOL',
            'birthday' => '2002-03-11',
            'email' => 'employee@gmail.com',
            'password' => 'password',
            'position' => 'Ojt',
            'role' => 'employee',
        ]);
    
        $employees = [
            [ '1987-11-05' , 'ellelee130613@gmail.com' , 'PARTOSA' ,'employee' , 'LENNOIRE' , '05861', 'LADY LENNOIRE', 'ABAD' , 'LGOO VI', 'LENNOIRE.jpg', 'female'],
            [ '1979-07-04' , 'normansamsheikh@gmail.com' , 'LOQUELLANO' ,'employee' , 'NORMAN' , '06793', 'NORMAN', 'ALI' , 'LGOO VI', 'NORMAN.jpg', 'male'],
            [ '1983-12-23' , 'jjjamihan@gmail.com' , 'PORTRIAS' ,'employee' , 'JENNIFER' , '05871', 'JENNIFER', 'AMIHAN' , 'LGOO VI', 'JENNIFER.jpg', 'female'],
            [ '1999-10-09' , 'leraveanoc@gmail.com' , 'OGUIS' ,'admin' , 'LERAVE' , 'COS1', 'LERAVE', 'ANOC' , 'AA II', 'LERAVE.png', 'female'],
            [ '1987-06-03' , 'nkkdryaranas@gmail.com' , 'A' ,'employee' , 'NIKKO' , 'JO1', 'NIKKO AUDREY', 'ARANAS' , 'JO', 'NIKKO.jpg', 'male'],
            [ '1994-09-14' , 'aerismark@gmail.com' , 'BAGUIO' ,'employee' , 'MARK', 'COS2', 'ERIS MARK' , 'AYA-AY', 'PEO II', 'ERIS.jpg', 'male'],
            [ '1976-07-27' , 'joydear_31@yahoo.com' , 'BAUTISTA' ,'employee' , 'JOCELYN', '05821', 'JOCELYN', 'BANDALA', 'LGOO VI', 'JOCELYN.jpg', 'female'],
            [ '1983-04-10' , 'bangalaowilfrans@yahoo.com' , 'TELMO' ,'employee' , 'WILFRANS', '05874', 'WILFRANS', 'BANGALAO', 'LGOO VI', 'WILFRANS.jpg', 'female'],
            [ '1967-04-22' , 'rggbastes22@gmail.com' , 'GATAL' ,'employee' , 'REGINA', '05674', 'REGINA GINA', 'BASTES', 'LGOO VII', 'REGINA.jpg', 'female'],
            [ '1995-08-23' , 'karenbeniga23@gmail.com' , 'BETONIO' ,'employee' , 'KAREN', '05951', 'KAREN ANN', 'BENIGA', 'LGOO VI', 'KAREN.jpg', 'female'],
            [ '1992-09-03' , 'eacaballo9392@gmail.com' , 'CABALLO' ,'employee' , 'EUNICE', '05912', 'EUNICE ANNE', 'BONIEL', 'LGOO VI', 'EUNICE.jpg', 'female'],
            [ '1970-01-10' , 'nicbungabong@gmail.com' , 'PAREDES' ,'employee' , 'NICANOR', '05802', 'NICANOR', 'BUNGABONG', 'LGOO VI', 'NICANOR.jpg', 'male'],
            [ '1986-07-02' , 'mbcabanag@dilg.gov.ph' , 'BABOR' ,'employee' , 'MICHAEL', '05853', 'MICHAEL', 'CABANAG', 'LGOO VI', 'MICHAEL.jpg', 'male'],
            [ '1985-01-04' , 'cfcagampang@dilg.gov.ph' , 'FABIO' ,'employee' , 'CHRISTINE', '05877', 'CHRISTINE ROSE', 'CAGAMPANG', 'LGOO VI', 'CAGAMPANG.jpg', 'female'],
            [ '1964-08-31' , 'redem64@gmail.com' , 'GABATO' ,'employee' , 'REDEMCION', '05389', 'REDEMCION', 'CAG-ONG', 'LGOO VI', 'REDEMCION.jpg', 'female'],
            [ '1972-05-09' , 'jeancam51896@gmail.com' , 'CACHO' ,'employee' , 'JEANETTE', '05786', 'JEANETTE', 'CAMILOTES', 'LGOO III', 'JEANETTE.jpg', 'female'],
            [ '1988-02-08' , 'glendzcamp@gmail.com' , 'BARQUILLA' ,'employee' , 'GLENDA', 'COS3', 'GLENDA', 'CAMPECINO', 'ENGINEER II', 'CAMPECINO.jpg', 'female'],
            [ '1986-11-10' , 'falkens86@gmail.com' , 'QUINAL' ,'employee' , 'CARLOS', 'COS4', 'CARLOS FALCON', 'CELOSIA', 'ENGINEER II', 'CARLOS.jpg', 'male'],
            [ '1997-03-07' , 'enicccorona.dilg7@gmail.com' , 'CHIU' ,'employee' , 'ERIKA', '001', 'ERIKA NICOLE', 'CORONA', 'LGOO II', 'ERIKA.png', 'female'],
            [ '1966-01-01' , 'geralynchiucorona@gmail.com' , 'CHIU' ,'employee' , 'GERALYN', 'COS5', 'GERALYN JANETTE', 'CORONA', 'PEO I', 'GERALYN.jpg', 'female'],
            [ '1980-08-05' , 'asoyd0805dilg@gmail.com' , 'POQUITA' ,'employee' , 'DYOSA', '05856', 'DYOSA MARIE', 'COSARE', 'LGOO VI', 'DYOSA.jpg', 'female'],
            [ '1983-03-06' , 'sallygingo.jeps@gmail.com' , 'GINGO' ,'employee' , 'ROSALINDA', '002', 'ROSALINDA', 'DAHUNOG', 'LGOO II', 'ROSALINDA.jpg', 'female'],
            [ '1986-11-07' , 'adalida.dilgbohol@gmail.com' , 'VILLAMOR' ,'employee' , 'ANTHONY', '05857', 'ANTHONY DEI', 'DALIDA', 'LGOO VI', 'ANTHONY.jpg', 'male'],
            [ '1978-06-27' , 'adonisdilg@gmail.com' , 'RAFOLS' ,'employee' , 'ADONIS', '05828', 'ADONIS', 'DAMALERIO', 'LGOO VI', 'ADONIS.jpg', 'male'],
            [ null , 'bedamdilg1983@gmail.com' , 'PERNIA' ,'employee' , 'BENIGNA', 'JO2', 'BENIGNA', 'DAMASIN', 'JO', '', 'female'],
            [ '1978-07-18' , 'jgrdominguez78@gmail.com' , 'RULONA' ,'employee' , 'JUDY', '05858', 'JUDY GRACE', 'DOMINGUEZ', 'LGOO VI', 'JUDY.jpg', 'female'],
            [ '1988-08-24' , 'cbebojo@dilg.gov.ph' , 'BONGALOS' ,'employee' , 'CLYDE', '05876', 'CLYDE', 'EBOJO', 'LGOO VI', 'CLYDE.jpg', 'male'],
            [ '1989-12-11' , 'simple.philjoy1@gmail.com' , 'ORIOQUE' ,'employee' , 'RHEA', '05888', 'RHEA JOY', 'FIGUEROA', 'LGOO VI', 'RHEA.jpg', 'female'],
            [ '1982-07-26' , 'mcardinoza72682@gmail.com' , 'CARDINOZA' ,'hr' , 'MYLOVE', '05855', 'MYLOVE', 'FLOOD', 'LGOO VI', 'MYLOVE.jpg', 'female'],
            [ '1989-06-27' , 'hyacinthgarrote@yahoo.com' , 'PONDOC' ,'employee' , 'HYACINTH', '05879', 'HYACINTH', 'GARROTE', 'LGOO VI', 'HYACINTH.jpg', 'female'],
            [ '1997-05-15  ' , 'hecellmae@gmail.com' , 'NISNISAN' ,'employee' , 'MEAH', '06030', 'MEAH HECELL', 'GENOVIA', 'LGOO II', 'MEAH.jpg', 'female'],
            [ '1975-07-25' , 'maninz31@yahoo.com' , 'DATAHAN' ,'employee' , 'RUEL', 'COS6', 'RUEL', 'GO', 'ENGINEER II', 'RUEL.jpg', 'male'],
            [ '1968-01-25' , 'gonzalesmoom@gmail.com' , 'GORGONIO' ,'supervisor' , 'JEROME', '05675', 'JEROME', 'GONZALES', 'LGOO VIII', 'JEROME.jpg', 'male'],
            [ '1976-05-20' , 'fvhalasan@dilg.gov.ph' , 'VIRADOR' ,'employee' , 'FLORENCIO JR.', '05805', 'FLORENCIO JR.', 'HALASAN', 'LGOO VI', 'FLORENCIO.jpg', 'male'],
            [ '1974-10-18' , 'ladysshh@gmail.com' , 'MARIMON' ,'employee' , 'MA. SHARON', '05130', 'MA. SHARON', 'HALASAN', 'LGOO VI', 'SHARON.jpg', 'female'],
            [ '1983-04-20' , 'mrsripe143@gmail.com' , 'TORRALBA' ,'employee' , 'MONA', '05863', 'MONA LISSA', 'HINOG', 'LGOO VI', 'MONA.jpg', 'female'],
            [ '1979-08-16' , 'vinceigcalinos79@gmail.com' , 'TIBORDO' ,'employee' , 'ISMAEL', '05783', 'ISMAEL VINCENT', 'IGCALINOS', 'LGOO VI', 'ISMAEL.jpg', 'male'],
            [ '1985-12-17' , 'jbighot@dilg.gov.ph' , 'BORELLA' ,'employee' , 'JED', '05993', 'JED', 'IGHOT', 'LGOO III', 'JED.jpg', 'male'],
            [ '1988-12-01' , 'dribingles@gmail.com' , 'BETE' ,'employee' , 'LAURENCE', '05881', 'DRIB LAURENCE', 'INGLES', 'LGOO VI', 'DRIB.png', 'male'],
            [ '1994-01-17' , 'daiyog@dilg.gov.ph' , 'APAO' ,'employee' , 'DIOLITO', '05968', 'DIOLITO', 'IYOG', 'LGOO V', '', 'male'],
            [ '1963-07-15' , 'maurajustol14344@gmail.com' , 'MONILLAS' ,'employee' , 'MAURA', '05789', 'MAURA', 'JUSTOL', 'LGOO VI', 'MAURA.jpg', 'female'],
            [ '1995-04-10' , 'kaindoyleejoshua@gmail.com' , 'ARIATA' ,'employee' , 'LEE', '05997', 'LEE JOSHUA', 'KAINDOY', 'ADA IV', 'LEE.jpg', 'male'],
            [ '1972-07-04' , 'uldarickladores@gmail.com' , 'CAGATA' ,'employee' , 'ULDARICK', '05691', 'ULDARICK', 'LADORES', 'ADA VI', 'ULDARICK.jpg', 'male'],
            [ '1978-06-12' , 'galaude@dilg.gov.ph' , 'ASOY' ,'employee' , 'GLENDA', '05840', 'GLENDA', 'LAUDE', 'LGOO VI', 'GLENDA.jpg', 'female'],
            [ '1985-05-25' , 'marialuzlintua@gmail.com' , 'ESTOQUE' ,'employee' , 'MARIA', '05873', 'MARIA LUZ', 'LINTUA', 'LGOO VI', 'MARIA.jpg', 'female'],
            [ '1970-05-19' , 'angelo.dilg2002@gmail.com' , 'SEPALON' ,'employee' , 'ANGELO', '05781', 'ANGELO', 'MAHINAY', 'LGOO VI', 'ANGELO.jpg', 'male'],
            [ '1977-12-14' , 'elvirabmandin@gmail.com' , 'BASTES' ,'employee' , 'ELVIRA', '05836', 'ELVIRA', 'MANDIN', 'LGOO VI', 'ELVIRA.jpg', 'female'],
            [ '1981-09-26' , 'asitaulava27@gmail.com' , 'MANONGAS' ,'employee' , 'RUBEN', '05972', 'RUBEN', 'MANLANGIT', 'ADA IV', 'RUBEN.jpg', 'male'],
            [ '1976-08-13' , 'tnmascarinas46@gmail.com' , 'NALAM' ,'employee' , 'TED', '05731', 'TED', 'MASCARINAS', 'LGOO VI', 'TED.jpg', 'male'],
            [ '2000-01-04' , 'medequisokhanda@gmail.com' , 'GARROTE' ,'admin' , 'KHANDA', 'COS7', 'KHANDA', 'MEDEQUISO', 'AA II', 'KHANDA.png', 'female'],
            [ '1978-11-26' , 'rickymonz37@gmail.com' , 'OMPAD' ,'employee' , 'RICARDO JR.', 'COS8', 'RICARDO JR.', 'MONTANEZ', 'ENGINEER II', 'RICARDO.jpg', 'male'],
            [ '1988-01-17' , 'christinenina17@gmail.com' , 'PENALES' ,'employee' , 'NINA', '05882', 'NINA CHRISTINE', 'MONTEJO', 'LGOO VI', 'NINA.jpg', 'female'],
            [ '1979-07-28' , 'eisoj79@yahoo.com' , 'MARFE' ,'employee' , 'JOSIE', '05774', 'JOSIE', 'MONTES', 'LGOO VI', 'JOSIE.jpg', 'female'],
            [ '1974-04-24' , 'fidelnarisma@gmail.com' , 'MARAGAÑAS' ,'employee' , 'FIDEL', '05792', 'FIDEL', 'NARISMA', 'LGOO VI', 'FIDEL.jpg', 'male'],
            [ '1966-11-22' , 'nisnisancecilio@gmail.com' , 'SUMAOY' ,'employee' , 'CECILIO', '05804', 'CECILIO', 'NISNISAN', 'LGOO VI', 'CECILIO.jpg', 'male'],
            [ '1989-07-22' , 'paredesjuliemae89@gmail.com' , 'PAREDES' ,'employee' , 'JULIE', '05936', 'JULIE MAE', 'NOMBRE', 'LGOO V', '', 'female'],
            [ '1969-09-30' , 'jcolalo@dilg.gov.ph' , 'CADUYAC' ,'employee' , 'JULIET', '05699', 'JULIET', 'OLALO', 'LGOO VI', 'JULIET.jpg', 'female'],
            [ '1995-05-31' , 'eyafoliquino@gmail.com' , 'ADANZA' ,'employee' , 'FAYE', '003', 'FAYE ARIELLE', 'OLIQUINO', 'LGOO II', 'FAYE.jpg', 'female'],
            [ '1987-06-28' , 'jopacleb@dilg.gov.ph' , 'OLAER' ,'employee' , 'JUN', '05995', 'JUN ARCY', 'PACLEB', 'LGOO III', 'PACLEB.jpg', 'male'],
            [ '1972-04-22' , 'ate_ye@yahoo.com' , 'ABELLANA' ,'employee' , 'MA. REINA', '05697', 'MA. REINA', 'QUILAS', 'LGOO VII', 'REINA.jpg', 'female'],
            [ '1968-06-02' , 'rubentrina2004@gmail.com' , 'HIMALALOAN' ,'employee' , 'JOSE', '05847', 'JOSE RUBEN', 'RACHO', 'LGOO VI', 'JOSE.jpg', 'male'],
            [ '1982-07-15' , 'emzkieruns@gmail.com' , 'FUERTES' ,'employee' , 'EMMYLOU', '05878', 'EMMYLOU', 'RAMA', 'LGOO VI', 'EMMYLOU.jpg', 'female'],
            [ '1984-03-20' , 'anatheresagotardo@gmail.com' , 'GOTARDO' ,'employee' , 'ANA', '05880', 'ANA THERESA', 'RASONABE', 'LGOO VI', 'ANA.jpg', 'female'],
            [ '1971-10-13' , 'leizl1013@yahoo.com' , 'CASEÑAS' ,'employee' , 'MA. LEIZL', '05793', 'MA. LEIZL', 'REDITA', 'AAS II', 'LEIZL.jpg', 'female'],
            [ '1988-10-12' , 'skromanillos@gmail.com' , 'GARROTE' ,'employee' , 'SARAH', '05957', 'SARAH KRISTINA', 'ROMANILLOS', 'LGOO VI', 'SARAH.jpg', 'female'],
            [ '1980-04-07' , 'snopegoj.jar@gmail.com' , 'ABARQUEZ' ,'employee' , 'JOGEPONS', '05803', 'JOGEPONS', 'RULOMA', 'LGOO VI', 'JOGEPONS.png', 'male'],
            [ '1981-06-19' , 'dilgians@gmail.com' , 'AUTENTICO' ,'employee' , 'IAN', '05862', 'JUN IAN', 'SURIC', 'LGOO VI', 'IAN.jpg', 'male'],
            [ '1968-07-05' , 'jptaningco@dilg.gov.ph' , 'PIQUERO' ,'employee' , 'JEKERI', '05713', 'JOSE JEKERI', 'TANINGCO', 'LGOO VI', 'JEKERI.jpg', 'male'],
            [ '1990-01-29' , 'jrtelmo@dilg.gov.ph' , 'RIOS' ,'employee' , 'JOYCELOU', '05994', 'JOYCELOU', 'TELMO', 'LGOO III', 'JOYCELOU.jpg', 'female'],
            [ '1998-11-02' , 'oznertorero@gmail.com' , 'CADUYAC' ,'employee' , 'LORENZO', '004', 'LORENZO', 'TORERO', 'LGOO II', 'LORENZO.jpg', 'male'],
            [ '1988-11-17' , 'srchel17@gmail.com' , 'SALOMON' ,'employee' , 'RACHEL', '05899', 'RACHEL', 'TORREMOCHA', 'LGOO VI', 'RACHEL.jpg', 'female'],
            [ '1998-01-01' , 'jessajan22@gmail.com' , 'ABING' ,'employee' , 'JESSA', '005', 'JESSA JAN', 'TRAZO', 'LGOO II', 'JESSA.jpg', 'female'],
            [ '1984-11-12' , 'jbtumale@dilg.gov.ph' , 'BARAJAN' ,'employee' , 'JAYSON', '05869', 'JAYSON', 'TUMALE', 'LGOO VI', 'JAYSON.jpg', 'male'],
            [ '1966-07-29' , 'bairoelbai@gmail.com' , 'ANDOY' ,'employee' , 'RHOEL', '05706', 'RHOEL', 'TUMARAO', 'LGOO VI', 'RHOEL.jpg', 'male'],
            [ '2002-06-20' , 'bruceunabia2000@gmail.com' , 'ROA' ,'employee' , 'BRUCE', '', 'BRUCE', 'UNABIA', 'ISA I', 'BRUCE.jpg', 'male'],
            [ '1978-06-04' , 'npunajan@dilg.gov.ph' , 'PAINAGAN' ,'employee' , 'NILDA', '05839', 'NILDA', 'UNAJAN', 'LGOO VI', 'NILDA.jpg', 'female'],
            [ '1981-12-30' , 'ashiira_dilg@yahoo.com' , 'APARECE' ,'employee' , 'MARY', '05819', 'MARY ANN', 'VERGA', 'LGOO VI', 'MARY.jpg', 'female'],
            [ '1985-03-14' , 'lavismanos@dilg.gov.ph' , 'ARCILLA' ,'employee' , 'LINDSEY', '05941', 'LINDSEY MARIE', 'VISMANOS', 'LGOO VI', 'LINDSEY.jpg', 'female'],
        ];

        $employees = array_map(function($employee) {
            $employee[4] = ucwords(strtolower($employee[4]));
            $employee[6] = ucwords(strtolower($employee[6]));
            $employee[2] = ucwords(strtolower($employee[2]));
            $employee[7] = ucwords(strtolower($employee[7]));
            return $employee;
        }, $employees);

        foreach ($employees as $employee) {
            User::factory()->create([
                'name' => $employee[4],
                'employee_code' => $employee[5],
                'first_name' => $employee[6],
                'middle_name' => $employee[2],
                'last_name' => $employee[7],
                'gender' => $employee[10],
                'department' => 'Provincial Office',
                'birthday' => $employee[0],
                'email' => $employee[1],
                'password' => bcrypt('password'),
                'role' => $employee[3],
                'position' => $employee[8],
                'profile_image' => $employee[9]
            ]);
        }   
        $this->call(YearlyHolidaysSeeder::class);
    }
}
