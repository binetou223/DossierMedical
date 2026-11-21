<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    Activity,
    BookOpen,
    Calendar,
    FileText,
    FolderGit2,
    LayoutGrid,
    ShieldAlert,
    UserCheck,
    UserPlus,
    Users,
} from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import type { NavItem } from '@/types';

const page = usePage();
const userRole = computed(() => page.props.auth?.user?.role);

const mainNavItems = computed<NavItem[]>(() => {
    const role = userRole.value;

    if (role === 'patient') {
        return [
            {
                title: 'Mon Dossier Médical',
                href: '/mon-dossier',
                icon: FileText,
            },
            {
                title: 'Mes Rendez-vous',
                href: '/rendez-vous',
                icon: Calendar,
            },
        ];
    }

    if (role === 'medecin') {
        return [
            {
                title: 'Agenda des Consultations',
                href: '/rendez-vous',
                icon: Calendar,
            },
            {
                title: 'Patients & Dossiers',
                href: '/patients',
                icon: Users,
            },
        ];
    }

    if (role === 'secretaire') {
        return [
            {
                title: 'Gestion des Rendez-vous',
                href: '/rendez-vous',
                icon: Calendar,
            },
            {
                title: 'Patients',
                href: '/patients',
                icon: Users,
            },
            {
                title: 'Nouveau Patient',
                href: '/patients/create',
                icon: UserPlus,
            },
        ];
    }

    if (role === 'admin') {
        return [
            {
                title: 'Tableau de bord',
                href: '/admin/dashboard',
                icon: LayoutGrid,
            },
            {
                title: 'Gestion Utilisateurs',
                href: '/admin/users',
                icon: UserCheck,
            },
            {
                title: "Journal d'Audit (CDP)",
                href: '/admin/audit',
                icon: ShieldAlert,
            },
            {
                title: 'Patients',
                href: '/patients',
                icon: Users,
            },
            {
                title: 'Rendez-vous',
                href: '/rendez-vous',
                icon: Calendar,
            },
        ];
    }

    return [
        {
            title: 'Tableau de bord',
            href: '/dashboard',
            icon: LayoutGrid,
        },
    ];
});

const footerNavItems: NavItem[] = [
    {
        title: 'Plan Directeur',
        href: '/docs/PLAN_REALISATION_SOLID.md',
        icon: BookOpen,
    },
    {
        title: 'Dépôt GitHub',
        href: 'https://github.com/binetou223/DossierMedical',
        icon: FolderGit2,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link href="/dashboard">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
