import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ChartConfig, ChartContainer, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';
import { CartesianGrid, Line, LineChart, ReferenceLine, XAxis, YAxis } from 'recharts';

interface TestDataPoint {
    test_date: string;
    rto_minutes: number;
    rpo_minutes: number;
}

interface TrendChartProps {
    data: TestDataPoint[];
    rtoTarget?: number;
    rpoTarget?: number;
}

const chartConfig = {
    rto_minutes: {
        label: 'RTO',
        color: 'var(--chart-1)',
    },
    rpo_minutes: {
        label: 'RPO',
        color: 'var(--chart-2)',
    },
} satisfies ChartConfig;

export function TrendChart({ data, rtoTarget, rpoTarget }: TrendChartProps) {
    if (data.length === 0) {
        return (
            <Card className="flex h-full min-h-80 flex-col">
                <CardHeader>
                    <CardTitle>RTO/RPO Trend</CardTitle>
                    <CardDescription>Track recovery metrics over time</CardDescription>
                </CardHeader>
                <CardContent className="flex flex-1 items-center justify-center">
                    <p className="text-muted-foreground">No DR test data available. Add a test to see trends.</p>
                </CardContent>
            </Card>
        );
    }

    return (
        <Card className="flex h-full min-h-80 flex-col">
            <CardHeader>
                <CardTitle>RTO/RPO Trend</CardTitle>
                <CardDescription>Recovery Time and Point Objectives over time</CardDescription>
            </CardHeader>
            <CardContent className="flex-1">
                <ChartContainer config={chartConfig} className="h-full w-full">
                    <LineChart accessibilityLayer data={data}>
                        <CartesianGrid strokeDasharray="3 3" vertical={false} />
                        <XAxis
                            dataKey="test_date"
                            tickLine={false}
                            axisLine={false}
                            tickMargin={8}
                            tickFormatter={(value) => {
                                const date = new Date(value);
                                return date.toLocaleDateString('en-US', {
                                    month: 'short',
                                    day: 'numeric',
                                });
                            }}
                        />
                        <YAxis tickLine={false} axisLine={false} tickMargin={8} tickFormatter={(value) => `${value} min`} />
                        <ChartTooltip
                            content={
                                <ChartTooltipContent
                                    labelFormatter={(value) => {
                                        const date = new Date(value);
                                        return date.toLocaleDateString('en-US', {
                                            month: 'long',
                                            day: 'numeric',
                                            year: 'numeric',
                                        });
                                    }}
                                />
                            }
                        />
                        <Line
                            type="monotone"
                            dataKey="rto_minutes"
                            stroke="var(--color-rto_minutes)"
                            strokeWidth={2}
                            dot={{ fill: 'var(--color-rto_minutes)' }}
                            activeDot={{ r: 6 }}
                        />
                        <Line
                            type="monotone"
                            dataKey="rpo_minutes"
                            stroke="var(--color-rpo_minutes)"
                            strokeWidth={2}
                            dot={{ fill: 'var(--color-rpo_minutes)' }}
                            activeDot={{ r: 6 }}
                        />
                        {rtoTarget !== undefined && (
                            <ReferenceLine
                                y={rtoTarget}
                                stroke="var(--color-rto_minutes)"
                                strokeDasharray="5 5"
                                label={{
                                    value: `RTO Target: ${rtoTarget} min`,
                                    position: 'insideTopRight',
                                    fill: 'var(--color-rto_minutes)',
                                    fontSize: 12,
                                }}
                            />
                        )}
                        {rpoTarget !== undefined && (
                            <ReferenceLine
                                y={rpoTarget}
                                stroke="var(--color-rpo_minutes)"
                                strokeDasharray="5 5"
                                label={{
                                    value: `RPO Target: ${rpoTarget} min`,
                                    position: 'insideBottomRight',
                                    fill: 'var(--color-rpo_minutes)',
                                    fontSize: 12,
                                }}
                            />
                        )}
                    </LineChart>
                </ChartContainer>
            </CardContent>
        </Card>
    );
}
