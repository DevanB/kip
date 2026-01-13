import { RpoWidget } from '@/components/rpo-widget';
import { RtoWidget } from '@/components/rto-widget';
import { TrendChart } from '@/components/trend-chart';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { create } from '@/actions/App/Http/Controllers/DrTestController';
import { index as drTestsIndex } from '@/routes/dr-tests';
import { edit as kpiTargetsEdit } from '@/routes/kpi-targets';
import { Head, Link } from '@inertiajs/react';
import { History, PlusCircle, Settings } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'DR KPI Dashboard',
        href: dashboard().url,
    },
];

interface TestDataPoint {
    test_date: string;
    rto_minutes: number;
    rpo_minutes: number;
}

interface DashboardProps {
    latestRto?: number | null;
    targetRto?: number;
    latestRpo?: number | null;
    targetRpo?: number;
    testData?: TestDataPoint[];
}

export default function Dashboard({ latestRto = null, targetRto = 60, latestRpo = null, targetRpo = 60, testData = [] }: DashboardProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="DR KPI Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">DR KPI Dashboard</h1>
                    <div className="flex items-center gap-2">
                        <Button variant="outline" asChild>
                            <Link href={drTestsIndex().url}>
                                <History className="size-4" />
                                History
                            </Link>
                        </Button>
                        <Button variant="outline" asChild>
                            <Link href={kpiTargetsEdit().url}>
                                <Settings className="size-4" />
                                Settings
                            </Link>
                        </Button>
                        <Button asChild>
                            <Link href={create().url}>
                                <PlusCircle className="size-4" />
                                Add DR Test
                            </Link>
                        </Button>
                    </div>
                </div>
                <div className="grid auto-rows-min gap-4 md:grid-cols-2">
                    <RtoWidget latestRto={latestRto} targetRto={targetRto} />
                    <RpoWidget latestRpo={latestRpo} targetRpo={targetRpo} />
                </div>
                <TrendChart data={testData} rtoTarget={targetRto} rpoTarget={targetRpo} />
            </div>
        </AppLayout>
    );
}
