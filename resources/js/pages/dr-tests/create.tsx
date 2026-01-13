import { store } from '@/actions/App/Http/Controllers/DrTestController';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { create } from '@/routes/dr-tests';
import { type BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Add DR Test',
        href: create().url,
    },
];

export default function Create() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Add DR Test" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold">Add DR Test</h1>
                <Card className="max-w-2xl">
                    <CardHeader>
                        <CardTitle>DR Test Details</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Form {...store.form()} className="space-y-6">
                            {({ processing, errors }) => (
                                <>
                                    <div className="grid gap-2">
                                        <Label htmlFor="test_date">Test Date</Label>
                                        <Input id="test_date" type="date" name="test_date" required />
                                        <InputError message={errors.test_date} />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="rto_minutes">RTO (minutes)</Label>
                                        <Input
                                            id="rto_minutes"
                                            type="number"
                                            name="rto_minutes"
                                            min="1"
                                            required
                                            placeholder="e.g., 45"
                                        />
                                        <InputError message={errors.rto_minutes} />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="rpo_minutes">RPO (minutes)</Label>
                                        <Input
                                            id="rpo_minutes"
                                            type="number"
                                            name="rpo_minutes"
                                            min="1"
                                            required
                                            placeholder="e.g., 30"
                                        />
                                        <InputError message={errors.rpo_minutes} />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="notes">Notes (optional)</Label>
                                        <textarea
                                            id="notes"
                                            name="notes"
                                            rows={4}
                                            className="border-input placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 flex w-full rounded-md border bg-transparent px-3 py-2 text-base shadow-xs outline-none focus-visible:ring-[3px] md:text-sm"
                                            placeholder="Add any additional notes about the DR test..."
                                        />
                                        <InputError message={errors.notes} />
                                    </div>

                                    <div className="flex items-center gap-4">
                                        <Button type="submit" disabled={processing}>
                                            {processing ? 'Saving...' : 'Save DR Test'}
                                        </Button>
                                    </div>
                                </>
                            )}
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
