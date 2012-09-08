#include <stdio.h>

int main(void)
{
	const float Revenue_Per_150 = 4.5f;
	short JanSold = 23500;
	short FebSold = 19300;
	short MarSold = 21600;
	short RevQuarter = 0.0f;
	
	short QuarterSold = JanSold + FebSold + MarSold;

	printf("\nStock Sold in \n Jan:%d\n Feb:%d\n Mar:%d\n", JanSold, FebSold, MarSold);
	printf("\nTotal stock sold in first quarter: %d", QuarterSold);

	RevQuarter = QuarterSold / 150 * Revenue_Per_150;
	printf("\nSales revenue this quarter id : $%.2f\n",RevQuarter);
	return 0;
}
