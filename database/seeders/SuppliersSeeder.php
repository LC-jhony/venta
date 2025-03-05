<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $suppliers = [
            ['name' => 'TechCorp Solutions', 'email' => 'contact@techcorp.com', 'phone' => '123-456-7890', 'address' => 'Calle Principal 123', 'status' => true, 'description' => 'Proveedor de equipos tecnológicos', 'country' => 'México', 'city' => 'Ciudad de México', 'state' => 'CDMX'],
            ['name' => 'Global Electronics', 'email' => 'sales@globalelec.com', 'phone' => '234-567-8901', 'address' => 'Av. Reforma 456', 'status' => true, 'description' => 'Distribuidor de componentes electrónicos', 'country' => 'México', 'city' => 'Guadalajara', 'state' => 'Jalisco'],
            ['name' => 'Office Supplies Pro', 'email' => 'info@officesupplies.com', 'phone' => '345-678-9012', 'address' => 'Blvd. Industrial 789', 'status' => true, 'description' => 'Suministros de oficina', 'country' => 'México', 'city' => 'Monterrey', 'state' => 'Nuevo León'],
            ['name' => 'Digital Solutions', 'email' => 'support@digitalsol.com', 'phone' => '456-789-0123', 'address' => 'Calle Tecnología 234', 'status' => true, 'description' => 'Servicios digitales', 'country' => 'México', 'city' => 'Puebla', 'state' => 'Puebla'],
            ['name' => 'Hardware Plus', 'email' => 'sales@hardwareplus.com', 'phone' => '567-890-1234', 'address' => 'Av. Central 567', 'status' => true, 'description' => 'Hardware y herramientas', 'country' => 'México', 'city' => 'Querétaro', 'state' => 'Querétaro'],
            ['name' => 'Network Systems', 'email' => 'info@networksys.com', 'phone' => '678-901-2345', 'address' => 'Calle Red 890', 'status' => true, 'description' => 'Equipos de red', 'country' => 'México', 'city' => 'Tijuana', 'state' => 'Baja California'],
            ['name' => 'Software Solutions', 'email' => 'contact@softwaresol.com', 'phone' => '789-012-3456', 'address' => 'Av. Desarrollo 123', 'status' => true, 'description' => 'Software empresarial', 'country' => 'México', 'city' => 'Mérida', 'state' => 'Yucatán'],
            ['name' => 'Data Systems', 'email' => 'info@datasys.com', 'phone' => '890-123-4567', 'address' => 'Blvd. Datos 456', 'status' => true, 'description' => 'Sistemas de datos', 'country' => 'México', 'city' => 'Cancún', 'state' => 'Quintana Roo'],
            ['name' => 'Tech Innovations', 'email' => 'sales@techinno.com', 'phone' => '901-234-5678', 'address' => 'Calle Innovación 789', 'status' => true, 'description' => 'Innovaciones tecnológicas', 'country' => 'México', 'city' => 'León', 'state' => 'Guanajuato'],
            ['name' => 'Office Tech', 'email' => 'support@officetech.com', 'phone' => '012-345-6789', 'address' => 'Av. Oficina 234', 'status' => true, 'description' => 'Tecnología de oficina', 'country' => 'México', 'city' => 'Toluca', 'state' => 'Estado de México'],
            ['name' => 'Smart Solutions', 'email' => 'info@smartsol.com', 'phone' => '123-456-7891', 'address' => 'Calle Smart 567', 'status' => true, 'description' => 'Soluciones inteligentes', 'country' => 'México', 'city' => 'Aguascalientes', 'state' => 'Aguascalientes'],
            ['name' => 'Digital World', 'email' => 'contact@digitalworld.com', 'phone' => '234-567-8902', 'address' => 'Blvd. Digital 890', 'status' => true, 'description' => 'Mundo digital', 'country' => 'México', 'city' => 'San Luis Potosí', 'state' => 'San Luis Potosí'],
            ['name' => 'Tech Support Pro', 'email' => 'help@techsupport.com', 'phone' => '345-678-9013', 'address' => 'Av. Soporte 123', 'status' => true, 'description' => 'Soporte técnico profesional', 'country' => 'México', 'city' => 'Morelia', 'state' => 'Michoacán'],
            ['name' => 'Computer Systems', 'email' => 'sales@computersys.com', 'phone' => '456-789-0124', 'address' => 'Calle Computación 456', 'status' => true, 'description' => 'Sistemas computacionales', 'country' => 'México', 'city' => 'Hermosillo', 'state' => 'Sonora'],
            ['name' => 'Network Pro', 'email' => 'info@networkpro.com', 'phone' => '567-890-1235', 'address' => 'Blvd. Redes 789', 'status' => true, 'description' => 'Redes profesionales', 'country' => 'México', 'city' => 'Culiacán', 'state' => 'Sinaloa'],
            ['name' => 'Tech Experts', 'email' => 'experts@techexp.com', 'phone' => '678-901-2346', 'address' => 'Av. Expertos 234', 'status' => true, 'description' => 'Expertos en tecnología', 'country' => 'México', 'city' => 'Chihuahua', 'state' => 'Chihuahua'],
            ['name' => 'Digital Services', 'email' => 'service@digitalserv.com', 'phone' => '789-012-3457', 'address' => 'Calle Servicios 567', 'status' => true, 'description' => 'Servicios digitales profesionales', 'country' => 'México', 'city' => 'Tampico', 'state' => 'Tamaulipas'],
            ['name' => 'IT Solutions', 'email' => 'solutions@itsol.com', 'phone' => '890-123-4568', 'address' => 'Blvd. IT 890', 'status' => true, 'description' => 'Soluciones de TI', 'country' => 'México', 'city' => 'Veracruz', 'state' => 'Veracruz'],
            ['name' => 'Tech Store', 'email' => 'store@techstore.com', 'phone' => '901-234-5679', 'address' => 'Av. Tienda 123', 'status' => true, 'description' => 'Tienda de tecnología', 'country' => 'México', 'city' => 'Acapulco', 'state' => 'Guerrero'],
            ['name' => 'System Integrators', 'email' => 'info@sysint.com', 'phone' => '012-345-6780', 'address' => 'Calle Sistemas 456', 'status' => true, 'description' => 'Integradores de sistemas', 'country' => 'México', 'city' => 'Oaxaca', 'state' => 'Oaxaca'],
            ['name' => 'Tech Warehouse', 'email' => 'warehouse@techware.com', 'phone' => '123-456-7892', 'address' => 'Blvd. Almacén 789', 'status' => true, 'description' => 'Almacén de tecnología', 'country' => 'México', 'city' => 'Durango', 'state' => 'Durango'],
            ['name' => 'Digital Equipment', 'email' => 'equipment@digitaleq.com', 'phone' => '234-567-8903', 'address' => 'Av. Equipos 234', 'status' => true, 'description' => 'Equipos digitales', 'country' => 'México', 'city' => 'Pachuca', 'state' => 'Hidalgo'],
            ['name' => 'Tech Import', 'email' => 'import@techimp.com', 'phone' => '345-678-9014', 'address' => 'Calle Importación 567', 'status' => true, 'description' => 'Importación de tecnología', 'country' => 'México', 'city' => 'Cuernavaca', 'state' => 'Morelos'],
            ['name' => 'Network Equipment', 'email' => 'sales@networkeq.com', 'phone' => '456-789-0125', 'address' => 'Blvd. Equipamiento 890', 'status' => true, 'description' => 'Equipos de red', 'country' => 'México', 'city' => 'Saltillo', 'state' => 'Coahuila'],
            ['name' => 'Tech Distribution', 'email' => 'dist@techdist.com', 'phone' => '567-890-1236', 'address' => 'Av. Distribución 123', 'status' => true, 'description' => 'Distribución de tecnología', 'country' => 'México', 'city' => 'Villahermosa', 'state' => 'Tabasco'],
            ['name' => 'Computer Parts', 'email' => 'parts@compparts.com', 'phone' => '678-901-2347', 'address' => 'Calle Partes 456', 'status' => true, 'description' => 'Partes de computadora', 'country' => 'México', 'city' => 'Tepic', 'state' => 'Nayarit'],
            ['name' => 'Tech Supplies', 'email' => 'supplies@techsup.com', 'phone' => '789-012-3458', 'address' => 'Blvd. Suministros 789', 'status' => true, 'description' => 'Suministros tecnológicos', 'country' => 'México', 'city' => 'Zacatecas', 'state' => 'Zacatecas'],
            ['name' => 'Digital Hardware', 'email' => 'hardware@digitalhw.com', 'phone' => '890-123-4569', 'address' => 'Av. Hardware 234', 'status' => true, 'description' => 'Hardware digital', 'country' => 'México', 'city' => 'Colima', 'state' => 'Colima'],
            ['name' => 'Tech Components', 'email' => 'components@techcomp.com', 'phone' => '901-234-5670', 'address' => 'Calle Componentes 567', 'status' => true, 'description' => 'Componentes tecnológicos', 'country' => 'México', 'city' => 'La Paz', 'state' => 'Baja California Sur'],
            ['name' => 'System Solutions', 'email' => 'solutions@syssol.com', 'phone' => '012-345-6781', 'address' => 'Blvd. Soluciones 890', 'status' => true, 'description' => 'Soluciones de sistemas', 'country' => 'México', 'city' => 'Campeche', 'state' => 'Campeche'],
            ['name' => 'Tech Accessories', 'email' => 'accessories@techacc.com', 'phone' => '123-456-7893', 'address' => 'Av. Accesorios 123', 'status' => true, 'description' => 'Accesorios tecnológicos', 'country' => 'México', 'city' => 'Tlaxcala', 'state' => 'Tlaxcala'],
            ['name' => 'Digital Solutions Pro', 'email' => 'pro@digitalsol.com', 'phone' => '234-567-8904', 'address' => 'Calle Digital 456', 'status' => true, 'description' => 'Soluciones digitales profesionales', 'country' => 'México', 'city' => 'Ciudad Victoria', 'state' => 'Tamaulipas'],
            ['name' => 'Tech Imports Plus', 'email' => 'plus@techimp.com', 'phone' => '345-678-9015', 'address' => 'Blvd. Importaciones 789', 'status' => true, 'description' => 'Importaciones tecnológicas plus', 'country' => 'México', 'city' => 'Mexicali', 'state' => 'Baja California'],
            ['name' => 'Network Solutions Pro', 'email' => 'pro@netsol.com', 'phone' => '456-789-0126', 'address' => 'Av. Redes 234', 'status' => true, 'description' => 'Soluciones de red profesionales', 'country' => 'México', 'city' => 'Los Mochis', 'state' => 'Sinaloa'],
            ['name' => 'Tech Distributors Plus', 'email' => 'plus@techdist.com', 'phone' => '567-890-1237', 'address' => 'Calle Distribuidores 567', 'status' => true, 'description' => 'Distribuidores tecnológicos plus', 'country' => 'México', 'city' => 'Celaya', 'state' => 'Guanajuato'],
            ['name' => 'Computer Solutions Pro', 'email' => 'pro@compsol.com', 'phone' => '678-901-2348', 'address' => 'Blvd. Computadoras 890', 'status' => true, 'description' => 'Soluciones computacionales profesionales', 'country' => 'México', 'city' => 'Irapuato', 'state' => 'Guanajuato'],
            ['name' => 'Tech Support Plus', 'email' => 'plus@techsup.com', 'phone' => '789-012-3459', 'address' => 'Av. Soporte 123', 'status' => true, 'description' => 'Soporte técnico plus', 'country' => 'México', 'city' => 'Uruapan', 'state' => 'Michoacán'],
            ['name' => 'Digital Equipment Pro', 'email' => 'pro@digeq.com', 'phone' => '890-123-4570', 'address' => 'Calle Equipos 456', 'status' => true, 'description' => 'Equipos digitales profesionales', 'country' => 'México', 'city' => 'Zamora', 'state' => 'Michoacán'],
            ['name' => 'Tech Components Plus', 'email' => 'plus@techcomp.com', 'phone' => '901-234-5671', 'address' => 'Blvd. Componentes 789', 'status' => true, 'description' => 'Componentes tecnológicos plus', 'country' => 'México', 'city' => 'Ciudad Obregón', 'state' => 'Sonora'],
            ['name' => 'System Integrators Pro', 'email' => 'pro@sysint.com', 'phone' => '012-345-6782', 'address' => 'Av. Integradores 234', 'status' => true, 'description' => 'Integradores de sistemas profesionales', 'country' => 'México', 'city' => 'Ensenada', 'state' => 'Baja California'],
            ['name' => 'Tech Warehouse Plus', 'email' => 'plus@techware.com', 'phone' => '123-456-7894', 'address' => 'Calle Almacén 567', 'status' => true, 'description' => 'Almacén tecnológico plus', 'country' => 'México', 'city' => 'San Juan del Río', 'state' => 'Querétaro'],
            ['name' => 'Digital Services Pro', 'email' => 'pro@digserv.com', 'phone' => '234-567-8905', 'address' => 'Blvd. Servicios 890', 'status' => true, 'description' => 'Servicios digitales profesionales', 'country' => 'México', 'city' => 'Tehuacán', 'state' => 'Puebla'],
            ['name' => 'IT Solutions Plus', 'email' => 'plus@itsol.com', 'phone' => '345-678-9016', 'address' => 'Av. IT 123', 'status' => true, 'description' => 'Soluciones IT plus', 'country' => 'México', 'city' => 'Córdoba', 'state' => 'Veracruz'],
            ['name' => 'Tech Store Pro', 'email' => 'pro@techstore.com', 'phone' => '456-789-0127', 'address' => 'Calle Tienda 456', 'status' => true, 'description' => 'Tienda tecnológica profesional', 'country' => 'México', 'city' => 'Orizaba', 'state' => 'Veracruz'],
            ['name' => 'System Solutions Plus', 'email' => 'plus@syssol.com', 'phone' => '567-890-1238', 'address' => 'Blvd. Sistemas 789', 'status' => true, 'description' => 'Soluciones de sistemas plus', 'country' => 'México', 'city' => 'Coatzacoalcos', 'state' => 'Veracruz'],
            ['name' => 'Tech Experts Pro', 'email' => 'pro@techexp.com', 'phone' => '678-901-2349', 'address' => 'Av. Expertos 234', 'status' => true, 'description' => 'Expertos tecnológicos profesionales', 'country' => 'México', 'city' => 'Minatitlán', 'state' => 'Veracruz'],
            ['name' => 'Digital World Plus', 'email' => 'plus@digworld.com', 'phone' => '789-012-3460', 'address' => 'Calle Mundial 567', 'status' => true, 'description' => 'Mundo digital plus', 'country' => 'México', 'city' => 'Poza Rica', 'state' => 'Veracruz'],
            ['name' => 'Tech Support Pro Plus', 'email' => 'proplus@techsup.com', 'phone' => '890-123-4571', 'address' => 'Blvd. Soporte 890', 'status' => true, 'description' => 'Soporte técnico profesional plus', 'country' => 'México', 'city' => 'Tuxpan', 'state' => 'Veracruz'],
            ['name' => 'Computer Systems Pro', 'email' => 'pro@compsys.com', 'phone' => '901-234-5672', 'address' => 'Av. Sistemas 123', 'status' => true, 'description' => 'Sistemas computacionales profesionales', 'country' => 'México', 'city' => 'Ciudad del Carmen', 'state' => 'Campeche'],
            ['name' => 'Network Pro Plus', 'email' => 'plus@netpro.com', 'phone' => '012-345-6783', 'address' => 'Calle Redes 456', 'status' => true, 'description' => 'Redes profesionales plus', 'country' => 'México', 'city' => 'Chetumal', 'state' => 'Quintana Roo'],
            ['name' => 'Tech Innovations Pro', 'email' => 'pro@techinno.com', 'phone' => '123-456-7895', 'address' => 'Blvd. Innovación 789', 'status' => true, 'description' => 'Innovaciones tecnológicas profesionales', 'country' => 'México', 'city' => 'Playa del Carmen', 'state' => 'Quintana Roo'],
            ['name' => 'Office Tech Plus', 'email' => 'plus@offtech.com', 'phone' => '234-567-8906', 'address' => 'Av. Oficina 234', 'status' => true, 'description' => 'Tecnología de oficina plus', 'country' => 'México', 'city' => 'Cozumel', 'state' => 'Quintana Roo'],
            ['name' => 'Smart Solutions Pro', 'email' => 'pro@smartsol.com', 'phone' => '345-678-9017', 'address' => 'Calle Smart 567', 'status' => true, 'description' => 'Soluciones inteligentes profesionales', 'country' => 'México', 'city' => 'Tulum', 'state' => 'Quintana Roo'],
            ['name' => 'Digital World Pro Plus', 'email' => 'proplus@digworld.com', 'phone' => '456-789-0128', 'address' => 'Blvd. Digital 890', 'status' => true, 'description' => 'Mundo digital profesional plus', 'country' => 'México', 'city' => 'Puerto Morelos', 'state' => 'Quintana Roo'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
