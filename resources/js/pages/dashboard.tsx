import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { RtoWidget } from '@/components/rto-widget';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'DR KPI Dashboard',
        href: dashboard().url,
    },
];

interface DashboardProps {
    latestRto?: number | null;
    targetRto?: number;
}

export default function Dashboard({ latestRto = null, targetRto = 60 }: DashboardProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="DR KPI Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold">DR KPI Dashboard</h1>
                <div className="grid auto-rows-min gap-4 md:grid-cols-2">
                    <RtoWidget latestRto={latestRto} targetRto={targetRto} />
                    <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                        <span className="absolute inset-0 flex items-center justify-center text-muted-foreground">
                            RPO Widget
                        </span>
                    </div>
                </div>
                <div className="relative min-h-80 flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    <span className="absolute inset-0 flex items-center justify-center text-muted-foreground">
                        Trend Chart
                    </span>
                </div>
            </div>
        </AppLayout>
    );
}
