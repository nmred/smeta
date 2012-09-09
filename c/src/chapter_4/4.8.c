#include <stdio.h>

int main(void)
{
	long sum = 0L;
	int i = 1;
	int count = 0;
	
	printf("\nEnter the number of integers to sum: ");
	scanf(" %d", &count);
	
	while(i <= count) {
		sum += i++;	
	}
	printf("\nTotal of the first %d numbers is %ld\n", count, sum);
	return 0;
}
