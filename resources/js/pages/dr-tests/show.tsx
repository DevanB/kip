import { edit } from '@/actions/App/Http/Controllers/DrTestController';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/dr-tests';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Pencil } from 'lucide-react';

interface Phase {
    id: number;
    title: string;
    started_at: string;
    finished_at: string;
    duration_minutes: number;
}

interface DrTest {
    id: number;
    test_date: string;
    rto_minutes: number;
    rpo_minutes: number;
    notes: string | null;
    phases: Phase[];
}

interface ShowProps {
    drTest: DrTest;
}

export default function Show({ drTest }: ShowProps) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'DR Tests',
            href: index().url,
        },
        {
            title: drTest.test_date,
            href: `/dr-tests/${drTest.id}`,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`DR Test - ${drTest.test_date}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between gap-4">
                    <Button variant="ghost" size="sm" asChild>
                        <Link href={index().url}>
                            <ArrowLeft className="size-4" />
                            Back to History
                        </Link>
                    </Button>
                    <Button variant="outline" size="sm" asChild>
                        <Link href={edit(drTest.id).url}>
                            <Pencil className="mr-1 size-4" />
                            Edit
                        </Link>
                    </Button>
                </div>

                <div className="grid gap-4 md:grid-cols-2">
                    <Card>
                        <CardHeader>
                            <CardTitle>Test Details</CardTitle>
                            <CardDescription>DR test conducted on {drTest.test_date}</CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <p className="text-muted-foreground text-sm">Test Date</p>
                                    <p className="text-lg font-medium">{drTest.test_date}</p>
                                </div>
                                <div>
                                    <p className="text-muted-foreground text-sm">RTO (minutes)</p>
                                    <p className="text-lg font-medium">{drTest.rto_minutes}</p>
                                </div>
                                <div>
                                    <p className="text-muted-foreground text-sm">RPO (minutes)</p>
                                    <p className="text-lg font-medium">{drTest.rpo_minutes}</p>
                                </div>
                            </div>
                            {drTest.notes && (
                                <div>
                                    <p className="text-muted-foreground text-sm">Notes</p>
                                    <p className="mt-1">{drTest.notes}</p>
                                </div>
                            )}
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Test Phases</CardTitle>
                            <CardDescription>{drTest.phases.length} phase(s) recorded</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Title</TableHead>
                                        <TableHead>Start Time</TableHead>
                                        <TableHead>Finish Time</TableHead>
                                        <TableHead className="text-right">Duration (min)</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {drTest.phases.map((phase) => (
                                        <TableRow key={phase.id}>
                                            <TableCell className="font-medium">{phase.title}</TableCell>
                                            <TableCell>{phase.started_at}</TableCell>
                                            <TableCell>{phase.finished_at}</TableCell>
                                            <TableCell className="text-right">{phase.duration_minutes}</TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
